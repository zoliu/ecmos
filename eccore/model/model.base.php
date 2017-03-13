<?php

if (!defined('IN_ECM')) {
	exit('403 Forbidden');
}

/* 模型相关常量定义 */
define('HAS_ONE', 1); //一对一关联
define('BELONGS_TO', 2); //属于关联
define('HAS_MANY', 3); //一对多关联
define('HAS_AND_BELONGS_TO_MANY', 4); //多对多关联
define('DROP_CONDITION_TRUNCATE', 'TRUNCATE'); //清空

/*
除本基类文件外，所有的模型类的类名的构造规则应该是模型名(首字母大写)+model组成，文件名必须是模型名+.model组成
如有一个用户模型，模型名为user，则其文件名应为user.model.php，类名为UserModel
 */
class BaseModel extends Object {
	private $params = array();
	var $db = null;

	/* 所映射的数据库表 */
	var $table = '';

	/* 主键 */
	var $prikey = '';

	/* 别名 */
	var $alias = '';

	/* 模型的名称 */
	var $_name = '';

	/* 表前缀 */
	var $_prefix = '';

	/* 数据验证规则 */
	var $_autov = array();

	/* 查询统计 */
	var $_last_query_count = -1;

	/* 临时保存已删除的数据 */
	var $_dropped_data = array();

	/* 关系(定义关系时，只有belongs_to以及has_and_belongs_to_many需要指定reverse反向关系) */
	var $_relation = array();

	var $last_sql;

	function __construct($params, $db) {
		$this->BaseModel($params, $db);
	}
	/**
	 *  构造函数
	 *
	 *  @author Garbin
	 *  @param  array  $params
	 *  @param  object $db
	 *  @return void
	 */
	function BaseModel($params, $db) {
		$this->db = &$db;
		!$this->alias && $this->alias = $this->table;
		$this->_prefix = DB_PREFIX;
		$this->table = $this->_prefix . $this->table;
		if (!empty($params)) {
			foreach ($params as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 *    获取模型名称
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function getName() {
		return $this->_name;
	}

	/**
	 *    获取单一一条记录
	 *
	 *    @author    Garbin
	 *    @param     mixed $params
	 *    @return    array
	 */
	function _get($params) {
		$data = $this->find($params);
		if (!is_array($data)) {
			return array();
		}

		return current($data);
	}

	function where($where) {
		!empty($where) ? $this->params['where'][] = ' and ' . $where : '';
		return $this;
	}
	function orWhere($where) {

		!empty($where) ? $this->params['where'][] = ' or ' . $where : '';
		return $this;
	}
	function limit($value) {
		!empty($value) ? $this->params['limit'] = $value : null;
		return $this;
	}
	function fields($value) {
		!empty($value) ? $this->params['fields'] = $value : null;
		return $this;
	}

	function orderBy($value) {
		!empty($value) ? $this->params['order'] = $value : null;
		return $this;
	}
	function count() {
		$this->params['count'] = true;
		return $this;
	}
	function join($value) {
		!empty($value) ? $this->params['joinstr'][] = $value : null;
		return $this;
	}

	function toLog() {
		global $debug_db;
		if ($debug_db) {
			echo $this->lastSql() . "<br><br>";
		}
	}
	function lastSql() {
		return $this->last_sql;
	}

	function find($params = '') {
		$params = $this->_parse($params);
		$data = $this->_find($params);
		$this->toLog();
		return $data;
	}
	function get($params = '') {
		$params = $this->_parse($params);
		$data = $this->_get($params);
		$this->toLog();
		return $data;
	}

	function _parse($params) {
		if (!empty($params)) {
			return $params;
		}

		if (isset($this->params['where']) && count($this->params['where']) > 0) {
			$this->params['conditions'] = ' 1=1 ' . implode(' ', $this->params['where']);
			unset($this->params['where']);
		}
		if (isset($this->params['joinstr']) && count($this->params['joinstr']) > 0) {
			$this->params['joinstr'] = implode(' ', $this->params['joinstr']);
		}
		return $this->params;
	}

	/**
	 * 根据id取得信息
	 *
	 * @param mix $id
	 * @return array
	 */
	function get_info($id) {
		$rows = $this->find(array(
			'conditions' => intval($id),
		));
		return $rows ? current($rows) : array();
	}

	/**
	 *  根据一定条件找出相关数据(不连接其它模型，直接通过JOIN语句来查询)
	 *
	 *  @author Garbin
	 *  @param  mixed  $params
	 *  @return array
	 */
	function _find($params = array()) {
		extract($this->_initFindParams($params));

		/* 字段(SELECT FROM) */
		$fields = $this->getRealFields($fields);
		$fields == '' && $fields = '*';

		$tables = $this->table . ' ' . $this->alias;

		/* 左联结(LEFT JOIN) */
		$join_result = $this->_joinModel($tables, $join);

		/* 原来为($join_result || $index_key)，忘了最初的用意，默认加上主键应该是只为了为获得索引的数组服务的，因此只跟索引键是否是主键有关 */
		if ($index_key == $this->prikey || (is_array($index_key) && in_array($this->prikey, $index_key))) {
			/* 如果索引键里有主键，则默认在要查询字段后加上主键 */
			$fields .= ",{$this->alias}.{$this->prikey}";
		}

		/* 条件(WHERE) */
		$conditions = $this->_getConditions($conditions, true);

		/* 排序(ORDER BY) */
		$order && $order = ' ORDER BY ' . $this->getRealFields($order);

		/* 分页(LIMIT) */
		$limit && $limit = ' LIMIT ' . $limit;

		$join_str = !empty($joinstr) ? $joinstr : '';

		if ($count) {

			$this->_updateLastQueryCount("SELECT COUNT(*) as c FROM {$tables}{$join_str}{$conditions}");

		}

		/* 完整的SQL */

		$sql = "SELECT {$fields} FROM {$tables}{$join_str}{$conditions}{$order}{$limit}";

		$this->last_sql = $sql;

		return $index_key ? $this->db->getAllWithIndex($sql, $index_key) :
		$this->db->getAll($sql);
	}

	function parseJoin($from_k, $to_k, $to_t, $from_t = '', $type = 'left', $to_alias = '') {

		$model = &m($to_t);

		if ($type == 'left') {

			$str = " LEFT JOIN [B] [b] on [b].[fk]=[a].[pk] ";

		} else {

			$str = " RIGHT JOIN [B] [b] on [b].[fk]=[a].[pk] ";

		}

		if (!empty($from_t)) {

			$fmodel = &m($from_t);

		}

		$alias = !empty($to_alias) ? $to_alias : $model->alias;

		$str = str_replace('[B]', $model->table, $str);

		$str = str_replace('[b]', $alias, $str);

		$str = str_replace('[fk]', $to_k, $str);

		$str = isset($fmodel) ? str_replace('[a]', $fmodel->alias, $str) : str_replace('[a]', $this->alias, $str);

		$str = str_replace('[pk]', $from_k, $str);

		return $str;

	}

	/**
	 *  关联查找多对多关系的记录
	 *
	 *  @author Garbin
	 *  @param  mixed  $params
	 *  @return array
	 */
	function findAll($params = array()) {
		$params = $this->_initFindParams($params);
		extract($params);
		$pri_data = $this->find($params); //先找出通过JOIN获得的数据集
		if (!empty($include) && !empty($pri_data)) {
			$ids = array();
			if ($index_key != $this->prikey) {
				foreach ($pri_data as $pk => $pd) {
					$ids[] = $pd[$this->prikey];
				}
			} else {
				$ids = array_keys($pri_data);
			}

			foreach ($include as $relation_name => $find_param) {
				if (is_string($find_param)) {
					$relation_name = $find_param;
					$find_param = array();
				}

				/* 依次获取关联数据，并将其放放主数据集中 */
				$related_data = $this->getRelatedData($relation_name, $ids, $find_param);

				is_array($related_data) && $pri_data = $this->assemble($relation_name, $related_data, $pri_data);
			}
		}

		return $pri_data;
	}

	/**
	 *    获取一对多，多对多的关联数据
	 *
	 *    @author    Garbin
	 *    @param     string $relation_name       关系名称
	 *    @param     array $ids         主表的主键值列表
	 *    @param     array $find_param  关联的条件
	 *    @return    void
	 */
	function getRelatedData($relation_name, $ids, $find_param = array()) {
		$relation_info = $this->getRelation($relation_name);
		$model = &m($relation_info['model']);
		if (empty($ids)) {
			$this->_error('no_ids_to_assoc', $model->getName());

			return false;
		}

		if ($relation_info['type'] != HAS_MANY && $relation_info['type'] != HAS_AND_BELONGS_TO_MANY) {
			$this->_error('invalid_assoc_model', $model->getName());

			return false;
		}

		$alias = $model->alias;
		/* 如果是多对多关系，则连接的表的别名为指定别名或中间表名，否则为模型的别名 */
		if ($relation_info['type'] == HAS_AND_BELONGS_TO_MANY) {
			$be_related = $model->getRelation($relation_info['reverse']);
			$alias = isset($be_related['alias']) ? $be_related['alias'] : $be_related['middle_table'];
		}

		/* 构造查询条件 */
		$conditions = $alias . '.' . $relation_info['foreign_key'] . ' ' . db_create_in($ids); //主键值限定
		$conditions .= $relation_info['ext_limit'] ?
		' AND ' . $this->_getExtLimit($relation_info['ext_limit'], $alias)
		: '';
		$conditions .= is_string($find_param['conditions']) ? ' AND ' . $find_param['conditions'] : '';
		$find_param['conditions'] = $conditions;

		/* 查询字段 */
		$find_param['fields'] = !empty($find_param['fields']) ?
		$find_param['fields'] . ',' . $alias . '.' . $relation_info['foreign_key']
		: '';
		switch ($relation_info['type']) {
		case HAS_MANY:
			break;
		case HAS_AND_BELONGS_TO_MANY:
			$find_param['join'] = !empty($find_param['join']) ?
			$find_param['join'] . ',' . $relation_info['reverse']
			: $relation_info['reverse'];
			empty($find_param['order']) && $find_param['order'] = $model->alias . ".{$model->prikey} DESC";
			$find_param['index_key'] = array($relation_info['foreign_key'], $model->prikey);
			break;
		}

		return $model->find($find_param);
	}

	/**
	 *  添加一条记录
	 *
	 *  @author Garbin
	 *  @param  array $data
	 *  @return mixed
	 */
	function add($data, $compatible = false) {
		if (empty($data) || !$this->dataEnough($data)) {
			return false;
		}

		$data = $this->_valid($data);
		if (!$data) {
			$this->_error('no_valid_data');
			return false;
		}
		$insert_info = $this->_getInsertInfo($data);
		$mode = $compatible ? 'REPLACE' : 'INSERT';

		$this->db->query("{$mode} INTO {$this->table}{$insert_info['fields']} VALUES{$insert_info['values']}");
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			if ($insert_info['length'] > 1) {
				for ($i = $insert_id; $i < $insert_id + $insert_info['length']; $i++) {
					$id[] = $i;
				}
			} else {
				/* 添加单条记录 */
				$id = $insert_id;
			}
		}

		return $id;
	}

	/**
	 *  添加多对多关联的中间表关联数据
	 *
	 *  @author Garbin
	 *  @param  string  $relation_name
	 *  @param  int     $id
	 *  @param  mixed   $ids
	 *  @return bool
	 */
	function createRelation($relation_name, $id, $ids) {
		return $this->_relationLink('create', $relation_name, $id, $ids);
	}

	/**
	 *    更新多对多关系中的关系数据
	 *
	 *    @author    Garbin
	 *    @param     string $rela
	 *    @param     int    $id
	 *    @param     mixed  $ids
	 *    @param     mixed  $update_values
	 *    @return    bool
	 */
	function updateRelation($relation_name, $id, $ids, $update_values) {
		return $this->_relationLink('update', $relation_name, $id, $ids, $update_values);
	}

	/**
	 *    去除多对多的关联链接
	 *
	 *    @author    Garbin
	 *    @param     string   $relation_name (欲删除关系名称)
	 *    @param     mixed    $conditions    条件
	 *    @param     array    $ids 关联模型的主键值集合(被拥有者ID列表),可为空
	 *    @return    bool
	 */
	function unlinkRelation($relation_name, $conditions, $ids = null) {
		return $this->_relationLink('drop', $relation_name, $conditions, $ids);
	}

	/**
	 *    对多对多关联表操作
	 *
	 *    @author    Garbin
	 *    @param
	 *    @return    void
	 */
	function _relationLink($action, $relation_name, $id, $ids, $update_values = array()) {
		if ((empty($ids) && $action == 'create') || !$id || !$relation_name) {
			$this->_error('relation_link_param_error');

			return false;
		}
		$relation_info = $this->getRelation($relation_name);
		if ($relation_info['type'] !== HAS_AND_BELONGS_TO_MANY) {
			/* 若不是多对多的关系，则不支持创建关系链接 */
			$this->_error('relation_link_not_support_type');

			return false;
		}

		/* 被关联模型的反向关联信息 */
		$model = &m($relation_info['model']);
		$be_related = $model->getRelation($relation_info['reverse']);
		if ($be_related['type'] !== HAS_AND_BELONGS_TO_MANY) {
			$this->_error('be_related_link_not_support_type');

			return false;
		}

		/* 开始对链接进行操作 */
		switch ($action) {
		/* 创建链接 */
		case 'create':
			$data = array();

			/* 形成一个统一的array(1, 2, 3)类的数组 */
			if (is_numeric($ids)) {
				$ids = array($ids);
			} elseif (is_string($ids)) {
				$ids = explode(',', $ids);
				array_unique($ids);
			}
			$ext_limit_data = is_array($relation_info['ext_limit']) ? $relation_info['ext_limit'] : array();
			/* 对数组进行分情况处理，如果是array(1, 2, 3)类的，则认为只指定了被关联表的主键值 */
			foreach ($ids as $key => $value) {
				$related_data = array();

				/* 本表在关联表中的外键值 */
				$related_data[$relation_info['foreign_key']] = $id;

				/* 指定了除了主键值外的其它值 */
				if (is_array($value)) {
					/* 外表在关联表中的外键值 */
					$related_data[$be_related['foreign_key']] = intval($key);

					/* 将索引数据与扩展数据合并 */
					$related_data = array_merge($related_data, $value);
				} else //仅指定了被关联表的主键值
				{
					/* 外表在关联表中的外键值 */
					$related_data[$be_related['foreign_key']] = intval($value);
				}

				/* 逐项添加 */
				$data[] = array_merge($related_data, $ext_limit_data);
			}
			$insert_info = $this->_getInsertInfo($data);

			/* 创建链接 */
			return $this->db->query("REPLACE INTO {$this->_prefix}{$relation_info['middle_table']}{$insert_info['fields']} VALUES{$insert_info['values']}");
			break;
		case 'update':
			if (empty($update_values)) {
				return false;
			}
			if (is_string($update_values)) {
				$update_fields = $update_values;
			} elseif (is_array($update_values)) {
				$update_fields = array();
				foreach ($update_values as $_field => $_value) {
					$update_fields[] = "{$_field}='{$_value}'";
				}
				$update_fields = implode(',', $update_fields);
			} else {
				return false;
			}

			return $this->db->query("UPDATE {$this->_prefix}{$relation_info['middle_table']} SET {$update_fields} WHERE {$relation_info['foreign_key']} = {$id} AND {$be_related['foreign_key']} " . db_create_in($ids));
			break;

		/* 删除链接 */
		case 'drop':
			/* 是数字，则认为，删除所有与$id有关联的关系*/
			if (is_numeric($id)) {
				/* 本表主键是一个值，则可以指定被关联模型的主键值 */
				$where = "{$relation_info['foreign_key']}=" . $id;

				/* 指定了被关联模型的主键值，则将该限定条件加上 */
				$where .= !empty($ids) ? " AND {$be_related['foreign_key']} " . db_create_in($ids) : '';
			} elseif (is_array($id)) //是一个数组，则认为删除所有与$id列表中的主键有关系的关系
			{
				/* 如果本表的主键是一个数组，则表示要删除所有外键为指定集合的关联，在这种情况下，无法指定被关联模型的主键值 */
				$where = "{$relation_info['foreign_key']} " . db_create_in($id);
			} elseif (is_string($id)) //是一个字符串，则认为是自定义的条件来限制操作
			{
				$where = $id;
			}

			$where .= is_array($relation_info['ext_limit']) ? ' AND ' . $this->_getExtLimit($relation_info['ext_limit']) : '';

			return $this->db->query("DELETE FROM {$this->_prefix}{$relation_info['middle_table']} WHERE {$where}");
			break;
		}

		return true;
	}

	/**
	 *  简化更新操作
	 *
	 *  @author Garbin
	 *  @param  array   $edit_data
	 *  @param  mixed   $conditions
	 *  @return bool
	 */
	function edit($conditions, $edit_data) {
		if (empty($edit_data)) {
			return false;
		}
		$edit_data = $this->_valid($edit_data);
		if (!$edit_data) {
			return false;
		}
		$edit_fields = $this->_getSetFields($edit_data);
		$conditions = $this->_getConditions($conditions, false);
		$this->db->query("UPDATE {$this->table} SET {$edit_fields}{$conditions}");

		return $this->db->affected_rows();

	}

	/**
	 *  简化删除记录操作
	 *
	 *  @author Garbin
	 *  @param  mixed $ids
	 *  @return int
	 */
	function drop($conditions, $fields = '') {
		if (empty($conditions)) {
			return;
		}
		if ($conditions === DROP_CONDITION_TRUNCATE) {
			$conditions = '';
		} else {
			$conditions = $this->_getConditions($conditions, false);
		}

		/* 保存删除的记录的主键值，便于关联删除时使用 */
		$fields && $fields = ',' . $fields;

		/* 这是个瓶颈，当删除的数据量非常大时会有问题 */
		$this->_saveDroppedData("SELECT {$this->prikey}{$fields} FROM {$this->table}{$conditions}");

		$droped_data = $this->getDroppedData();
		if (empty($droped_data)) {
			return 0;
		}

		$this->db->query("DELETE FROM {$this->table}{$conditions}");
		$affectedRows = $this->db->affected_rows();
		if ($affectedRows > 0) {
			/* 删除依赖数据 */
			$this->dropDependentData(array_keys($droped_data));
		}

		return $affectedRows;
	}

	/**
	 *  删除依赖数据
	 *
	 *  @author Garbin
	 *  @param  mixed $keys     本表的主键值集合
	 *  @return bool
	 */
	function dropDependentData($keys) {
		if (empty($keys)) {
			$this->_error('keys_is_empty');

			return false;
		}
		if (is_numeric($keys)) {
			$keys = array($keys);
		} elseif (is_string($keys)) {
			$keys = explode(',', $keys);
		}

		/* 获取所有关系 */
		$relation = $this->getRelation();
		if (empty($relation)) {
			return true;
		}

		/* 依次将关系中的依赖数据删除 */
		foreach ($relation as $relation_name => $relation_info) {
			/* 如果是多对多关系，则只解除关联表中的数据 */
			if ($relation_info['type'] === HAS_AND_BELONGS_TO_MANY) {
				$this->unlinkRelation($relation_name, $keys);
			} elseif ($relation_info['dependent'] && $relation_info['type'] !== BELONGS_TO) {
				/* 如果是指定了dependent依赖性，则调用drop删除之 */
				if ($relation_info['model'] != $this->_name) {
					/* 若关联的模型不是本身，则直接使用m取得模型对象 */
					$model = &m($relation_info['model']);
				} else {
					/* 否则要创建一个新的模型对象以避免操作时互相影响 */
					$model = &m($relation_info['model'], array(), true);
				}

				/* 开始删除 */
				$ext_limit = (isset($relation_info['ext_limit']) && $relation_info['ext_limit']) ? ' AND ' . $this->_getExtLimit($relation_info['ext_limit']) : '';

				/* 默认限定键为主键 */
				$limit_keys = $keys;

				/*当关联类型是一对一拥有的关系且设定了参考键时，说明外表的外键值不是本表的主键值，而是参考键的值，因此限定键是参考键，要找出参考键的值的集合*/
				if ($relation_info['type'] == HAS_ONE && isset($relation_info['refer_key'])) {
					/* 找出参考键值的集合,本表的参考键值集合 */
					$limit_keys = $this->db->getCol("SELECT DISTINCT {$relation_info['refer_key']} FROM {$this->table} WHERE {$this->prikey} " . db_create_in($keys));
					if ($limit_keys === false) {
						continue;
					}
				}

				/* 外表的外键=限定键(默认为主键)的都删除 */
				$conditions = "{$relation_info['foreign_key']} " . db_create_in($limit_keys) . $ext_limit;

				/* 删除数据 */
				$model->drop($conditions);
			}
		}
	}

	/**
	 *  获取扩展限制
	 *
	 *  @author Garbin
	 *  @param  array $ext_limit
	 *  @param  string $alias
	 *  @return string
	 */
	function _getExtLimit($ext_limit, $alias = null) {
		if (!$ext_limit) {
			return;
		}
		$str = '';
		$pre = '';
		if ($alias) {
			$pre = "{$alias}.";
		}
		foreach ($ext_limit as $_k => $_v) {
			$str .= $str == '' ? " {$pre}{$_k} = '{$_v}'" : " AND {$pre}{$_k} = '{$_v}'";
		}

		return $str;
	}

	/**
	 *  获取时时保存的已删除记录
	 *
	 *  @author Garbin
	 *  @return array
	 */
	function getDroppedData() {
		return $this->_dropped_data;
	}

	/**
	 *  获取统计数
	 *
	 *  @author Garbin
	 *  @return int
	 */
	function getCount() {
		return $this->_last_query_count;
	}

	/**
	 *  临时保存已删除的记录数据
	 *
	 *  @author Garbin
	 *  @param  string $sql
	 *  @return void
	 */
	function _saveDroppedData($sql) {
		$this->_dropped_data = $this->db->getAllWithIndex($sql, $this->prikey);
	}

	/**
	 *  更新查询统计数
	 *
	 *  @author Garbin
	 *  @param  string $sql
	 *  @return void
	 */
	function _updateLastQueryCount($sql) {
		$this->_last_query_count = $this->db->getOne($sql);
	}

	/**
	 *  获取条件句段
	 *
	 *  @author Garbin
	 *  @param  mixed   $conditions
	 *  @return string
	 */
	function _getConditions($conditions, $if_add_alias = false) {
		$alias = '';
		if ($if_add_alias) {
			$alias = $this->alias . '.';
		}
		if (is_numeric($conditions)) {
			/* 如果是一个数字或数字字符串，则认为其是主键值 */
			return " WHERE {$alias}{$this->prikey} = {$conditions}";
		} elseif (is_string($conditions)) {
			/* 如果是字符串，则认为其是SQL自定义条件 */
			if (substr($conditions, 0, 6) == 'index:') {
				$value = substr($conditions, 6);
				return $value ? " WHERE {$alias}{$this->prikey}='{$value}'" : '';
			} else {
				return $conditions ? ' WHERE ' . $conditions : '';
			}
		} elseif (is_array($conditions)) {
			/* 如果是数组，则认为其是一个主键集合 */
			if (empty($conditions)) {
				return '';
			}
			foreach ($conditions as $_k => $_v) {
				if (!$_v) {
					unset($conditions[$_k]);
				}
			}
			$conditions = array_unique($conditions);

			return ' WHERE ' . $alias . $this->prikey . ' ' . db_create_in($conditions);
		} elseif (is_null($conditions)) {
			return '';
		}
	}

	/**
	 *  获取设置字段
	 *
	 *  @author Garbin
	 *  @param  array $data
	 *  @return string
	 */
	function _getSetFields($data) {
		if (!is_array($data)) {
			return $data;
		}
		$fields = array();
		foreach ($data as $_k => $_v) {
			!is_array($_v) && $fields[] = "{$_k}='{$_v}'";
		}

		return implode(',', $fields);
	}

	/**
	 *    获取查询时的字段列表
	 *
	 *    @author    Garbin
	 *    @param     string $src_fields_list
	 *    @return    string
	 */
	function getRealFields($src_fields_list) {
		$fields = $src_fields_list;
		if (!$src_fields_list) {
			$fields = '';
		}
		if (PHP_VERSION < 5.5) {
			$fields = preg_replace('/([a-zA-Z0-9_]+)\.([a-zA-Z0-9_*]+)/e', "\$this->_getFieldTable('\\1') . '.\\2'", $fields);
		} else {
			$fields = preg_replace_callback('/([a-zA-Z0-9_]+)\.([a-zA-Z0-9_*]+)/', "self::getRealFields_preg_callback", $fields);
		}

		return $fields;
	}

	function getRealFields_preg_callback($matches) {
		return $this->_getFieldTable($matches[1]) . '.' . $matches[2];
	}

	/**
	 *    解析字段所属
	 *
	 *    @author    Garbin
	 *    @param     string $owner
	 *    @return    string
	 */
	function _getFieldTable($owner) {
		if ($owner == 'this') {
			return $this->alias;
		} else {
			$m = &m($owner);
			if ($m === false) {
				/* 若没有对象，则原样返回 */

				return $owner;
			}

			return $m->alias;
		}
	}

	/**
	 *  获取插入的数据SQL
	 *
	 *  @author Garbin
	 *  @param  array $data
	 *  @return string
	 */
	function _getInsertInfo($data) {
		reset($data);
		$fields = array();
		$values = array();
		$length = 1;
		if (key($data) === 0 && is_array($data[0])) {
			$length = count($data);
			foreach ($data as $_k => $_v) {
				foreach ($_v as $_f => $_fv) {
					$is_array = is_array($_fv);
					($_k == 0 && !$is_array) && $fields[] = $_f;
					!$is_array && $values[$_k][] = "'{$_fv}'";
				}
				$values[$_k] = '(' . implode(',', $values[$_k]) . ')';
			}
		} else {
			foreach ($data as $_k => $_v) {
				$is_array = is_array($_v);
				!$is_array && $fields[] = $_k;
				!$is_array && $values[] = "'{$_v}'";
			}
			$values = '(' . implode(',', $values) . ')';
		}
		$fields = '(' . implode(',', $fields) . ')';
		is_array($values) && $values = implode(',', $values);

		return compact('fields', 'values', 'length');
	}

	/**
	 *  验证数据合法性，当只验证vrule中指定的字段，并且只当$data中设置了其值时才验证
	 *
	 *  @author Garbin
	 *  @param  array $data
	 *  @return mixed
	 */
	function _valid($data) {
		if (empty($this->_autov) || empty($data) || !is_array($data)) {
			return $data;
		}
		$max = $filter = $reg = $default = $valid = '';
		reset($data);
		$is_multi = (key($data) === 0 && is_array($data[0]));
		if (!$is_multi) {
			$data = array($data);
		}
		foreach ($this->_autov as $_k => $_v) {
			if (is_array($_v)) {
				$required = (isset($_v['required']) && $_v['required']) ? true : false;
				$type = isset($this->_autov[$_k]['type']) ? $this->_autov[$_k]['type'] : 'string';
				$min = isset($this->_autov[$_k]['min']) ? $this->_autov[$_k]['min'] : 0;
				$max = isset($this->_autov[$_k]['max']) ? $this->_autov[$_k]['max'] : 0;
				$filter = isset($this->_autov[$_k]['filter']) ? $this->_autov[$_k]['filter'] : '';
				$valid = isset($this->_autov[$_k]['valid']) ? $this->_autov[$_k]['valid'] : '';
				$reg = isset($this->_autov[$_k]['reg']) ? $this->_autov[$_k]['reg'] : '';
				$default = isset($this->_autov[$_k]['default']) ? $this->_autov[$_k]['default'] : '';
			} else {
				preg_match_all('/([a-z]+)(\((\d+),(\d+)\))?/', $_v, $result);
				$type = $result[1];
				$min = $result[3];
				$max = $result[4];
			}
			foreach ($data as $_sk => $_sd) {
				$has_set = isset($data[$_sk][$_k]);
				if (!$has_set) {
					continue;
				}

				if ($required && $data[$_sk][$_k] == '') {
					$this->_error("required_field", $_k);

					return false;
				}

				/* 运行到此，说明该字段不是必填项可以为空 */

				$value = $data[$_sk][$_k];

				/* 默认值 */
				if (!$value && $default) {
					$data[$_sk][$_k] = function_exists($default) ? $default() : $default;
					continue;
				}

				/* 若还是空值，则没必要往下验证长度，正则，自定义和过滤，因为其已经是一个空值了 */
				if (!$value) {
					continue;
				}

				/* 大小|长度限制 */

				if ($type == 'string' && is_string($value)) //360cd.cn

				{
					$strlen = strlen($value);
					if ($min != 0 && $strlen < $min) {
						$this->_error('autov_length_lt_min', $_k);

						return false;
					}
					if ($max != 0 && $strlen > $max) {
						$this->_error('autov_length_gt_max', $_k);

						return false;
					}
				} else {
					if ($min != 0 && $value < $min) {
						$this->_error('autov_value_lt_min', $_k);

						return false;
					}
					if ($max != 0 && $value > $max) {
						$this->_error('autov_value_gt_max', $_k);

						return false;
					}
				}

				/* 正则 */
				if ($reg) {
					if (!preg_match($reg, $value)) {
						$this->_error('check_match_error', $_k);
						return false;
					}
				}

				/* 自定义验证 */
				if ($valid && function_exists($valid)) {
					$result = $valid($value);
					if ($result !== true) {
						$this->_error($result);

						return false;
					}
				}

				/* 过滤 */
				if ($filter) {
					$funs = explode(',', $filter);
					foreach ($funs as $fun) {
						function_exists($fun) && $value = $fun($value);
					}
					$data[$_sk][$_k] = $value;
				}
			}
		}
		if (!$is_multi) {
			$data = $data[0];
		}

		return $data;
	}

	/**
	 *  初始化查询参数
	 *
	 *  @author Garbin
	 *  @param  array $params
	 *  @return array
	 */
	function _initFindParams($params) {
		$arr = array(
			'include' => array(),
			'join' => '',
			'joinstr' => '',
			'conditions' => '',
			'order' => '',
			'fields' => '',
			'limit' => '',
			'count' => false,
			'index_key' => $this->prikey,
		);
		if (is_array($params)) {
			return array_merge($arr, $params);
		} else {
			$arr['conditions'] = $params;
			return $arr;
		}
	}

	/**
	 *  按指定的方式LEFT JOIN指定关系的表
	 *
	 *  @author Garbin
	 *  @param  string $table
	 *  @param  string $join_object
	 *  @return string
	 */
	function _joinModel(&$table, $join) {
		$result = false;
		if (empty($join)) {
			return false;
		}

		/* 获取要关联的关系名 */
		$relation = preg_split('/,\s*/', $join);
		array_walk($relation, create_function('&$item, $key', '$item=trim($item);'));

		foreach ($relation as $_r) {
			/* 获取关系信息 */
			if (!($_mi = $this->getRelation($_r))) {
				/* 没有该关系则跳过 */
				continue;
			}

			/* 关联关系为$_mi的模型 */
			$join_string = $this->_getJoinString($_mi);
			if ($join_string) {
				/* 连接 */
				$table .= $join_string;
				$result = true;
			}
		}

		return $result;
	}
	function _getJoinString($relation_info) {
		switch ($relation_info['type']) {
		case HAS_ONE:
			$model = &m($relation_info['model']);

			/* 联合限制 */
			$ext_limit = '';
			$relation_info['ext_limit'] && $ext_limit = ' AND ' . $this->_getExtLimit($relation_info['ext_limit'], $model->alias); //须加上当前被关联表的别名，因为有可能存在多个JOIN，并且可能存在同名字段。

			/* 获取参考键，默认是本表主键(直接拥有)，否则为间接拥有 */
			$refer_key = isset($relation_info['refer_key']) ? $relation_info['refer_key'] : $this->prikey;

			/* 本表参考键=外表外键 */
			return " LEFT JOIN {$model->table} {$model->alias} ON {$this->alias}.{$refer_key}={$model->alias}.{$relation_info['foreign_key']}{$ext_limit}";
			break;
		case BELONGS_TO:
			/* 属于关系与拥有是一个反向的关系 */
			$model = &m($relation_info['model']);
			$be_related = $model->getRelation($relation_info['reverse']);
			if (empty($be_related)) {
				/* 没有找到反向关系 */
				$this->_error('no_reverse_be_found', $relation_info['model']);

				return '';
			}
			$ext_limit = '';
			!empty($relation_info['ext_limit']) && $ext_limit = ' AND ' . $this->_getExtLimit($relation_info['ext_limit'], $this->alias);
			/* 获取参考键，默认是外表主键 */
			$refer_key = isset($be_related['refer_key']) ? $be_related['refer_key'] : $model->prikey;

			/* 本表外键=外表参考键 */
			return " LEFT JOIN {$model->table} {$model->alias} ON {$this->alias}.{$be_related['foreign_key']} = {$model->alias}.{$refer_key}{$ext_limit}";
			break;
		case HAS_AND_BELONGS_TO_MANY:
			/* 连接中间表，本表主键=中间表外键 */
			$malias = isset($relation_info['alias']) ? $relation_info['alias'] : $relation_info['middle_table'];
			$ext_limit = '';
			$relation_info['ext_limit'] && $ext_limit = ' AND ' . $this->_getExtLimit($relation_info['ext_limit'], $malias);
			return " LEFT JOIN {$this->_prefix}{$relation_info['middle_table']} {$malias} ON {$this->alias}.{$this->prikey} = {$malias}.{$relation_info['foreign_key']}{$ext_limit}";
			break;
		}
	}

	/**
	 *    获取关系信息
	 *
	 *    @author    Garbin
	 *    @param     string $relation_name
	 *    @return    array
	 */
	function getRelation($relation_name = null) {
		return !is_null($relation_name) ? $this->_relation[$relation_name] : $this->_relation;
	}

	/**
	 *    获取指定关系类型的关联信息
	 *
	 *    @author    Garbin
	 *    @param     int $relation
	 *    @return    array
	 */
	function getRelationByType($relation) {
		if (empty($relation)) {
			return $this->_relation; //返回所有关系
		}
		$arr = array();
		foreach ($this->_relation as $relation_name => $relation_info) {
			if ($relation_info['relation'] == $relation) {
				$arr[$relation_name] = $relation_info;
			}
		}

		return $arr;
	}

	/**
	 *  组合数据
	 *
	 *  @author Garbin
	 *  @param  string  $relation_name  关系名称
	 *  @param  array   $assoc_data     关联的数据
	 *  @param  array   $pri_data       主表数据
	 *  @return array
	 */
	function assemble($relation_name, $assoc_data, $pri_data) {
		if (empty($pri_data) || empty($assoc_data)) {
			$this->_error('assemble_data_empty');

			return $pri_data;
		}

		/* 获取关系信息 */
		$relation_info = $this->getRelation($relation_name);
		$model = &m($relation_info['model']);

		/* 循环主数据集 */
		foreach ($pri_data as $pk => $pd) {
			/* 循环从数据集 */
			foreach ($assoc_data as $ak => $ad) {
				/* 当主表的主键值与外表的的外键值相等时，将该外表的数据加入到主表数据中键为$model->alias的数组中 */
				if ($pd[$this->prikey] == $ad[$relation_info['foreign_key']]) {
					$pd[$model->alias][$ak] = $ad;
					unset($assoc_data[$ak]); //减少循环次数
				}
			}
			$pri_data[$pk] = $pd;
		}

		return $pri_data;
	}

	/**
	 *    检查数据是否足够
	 *
	 *    @author    Garbin
	 *    @param     array $data
	 *    @return    bool[true:足够,false:不足]
	 */
	function dataEnough($data) {
		$required_fields = $this->getRequiredFields();
		if (empty($required_fields)) {
			return true;
		}
		$is_multi = (key($data) === 0 && is_array($data[0]));
		foreach ($required_fields as $field) {
			if ($is_multi) {
				foreach ($data as $key => $value) {
					if (!isset($value[$field])) {
						$this->_error('data_not_enough', $field);

						return false;
					}
				}
			} else {
				if (!isset($data[$field])) {
					$this->_error('data_not_enough', $field);

					return false;
				}
			}
		}

		return true;
	}

	/**
	 *    获取必须的字段列表
	 *
	 *    @author    Garbin
	 *    @return    array
	 */
	function getRequiredFields() {
		$fields = array();
		if (is_array($this->_autov)) {
			foreach ($this->_autov as $key => $value) {
				if (isset($value['required']) && $value['required']) {
					$fields[] = $key;
				}
			}
		}

		return $fields;
	}

	/**
	 * 用于统计
	 */
	function getOne($sql) {
		return $this->db->getOne($sql);
	}
	function getRow($sql) {
		return $this->db->getRow($sql);
	}
	function getCol($sql) {
		return $this->db->getCol($sql);
	}
	function getAll($sql) {
		return $this->db->getAll($sql);
	}
}
?>