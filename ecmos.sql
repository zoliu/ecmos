/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : ecmos

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-08-19 15:10:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ecm_acategory`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_acategory`;
CREATE TABLE `ecm_acategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_acategory
-- ----------------------------
INSERT INTO `ecm_acategory` VALUES ('1', '商城帮助', '0', '0', 'help');
INSERT INTO `ecm_acategory` VALUES ('2', '商城公告', '0', '0', 'notice');
INSERT INTO `ecm_acategory` VALUES ('3', '内置文章', '0', '0', 'system');
INSERT INTO `ecm_acategory` VALUES ('4', '新手上路', '1', '1', null);
INSERT INTO `ecm_acategory` VALUES ('5', '支付方式', '1', '2', null);
INSERT INTO `ecm_acategory` VALUES ('6', '用户服务', '1', '3', null);
INSERT INTO `ecm_acategory` VALUES ('7', '售后服务', '1', '4', null);
INSERT INTO `ecm_acategory` VALUES ('8', '商家服务', '1', '5', null);

-- ----------------------------
-- Table structure for `ecm_address`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_address`;
CREATE TABLE `ecm_address` (
  `addr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `set_default` int(5) DEFAULT '0',
  `setdefault` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`addr_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_address
-- ----------------------------
INSERT INTO `ecm_address` VALUES ('1', '3', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', '1', '0');
INSERT INTO `ecm_address` VALUES ('6', '22', 'tiantian', '133', '中国	辽宁省	本溪', 'bzbvcbcvbcbv', '', '', '36456345654645645', '1', '0');
INSERT INTO `ecm_address` VALUES ('5', '21', 'summer', '44', '中国	上海市	长宁区', '上海市长宁区', '123456', '', '15882243695', '1', '0');
INSERT INTO `ecm_address` VALUES ('4', '3', '何时才', '12', '中国	北京市	门头沟', '门头沟', '61005', '122983747566', '', '0', '0');
INSERT INTO `ecm_address` VALUES ('7', '23', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', '1', '0');
INSERT INTO `ecm_address` VALUES ('8', '3', 'dddddddd', '358', '中国	四川省	成都', 'dasdsada', '610000', '', '15900000000', '0', '0');
INSERT INTO `ecm_address` VALUES ('10', '3', '哈哈1', '104', '中国	河北省', '放松放松的', '000000', '', '15822222222', '0', '0');

-- ----------------------------
-- Table structure for `ecm_all_statistics`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_all_statistics`;
CREATE TABLE `ecm_all_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales` int(11) DEFAULT NULL,
  `collects` int(11) DEFAULT NULL,
  `carts` int(11) DEFAULT NULL,
  `visits` int(11) DEFAULT NULL,
  `cancels` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `goodcomments` int(11) DEFAULT NULL,
  `normalcomments` int(11) DEFAULT NULL,
  `badcomments` int(11) DEFAULT NULL,
  `refunds` int(11) DEFAULT NULL,
  `moneys` decimal(20,0) DEFAULT NULL,
  `stype` tinyint(4) DEFAULT NULL,
  `sumdate` char(60) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `store_name` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_all_statistics
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_app`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_app`;
CREATE TABLE `ecm_app` (
  `app_name` char(100) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `app_tags` char(200) DEFAULT NULL,
  `app_desc` char(250) DEFAULT NULL,
  `app_content` text,
  `default_image` char(250) DEFAULT NULL,
  `install_num` int(11) DEFAULT NULL,
  `view_num` int(11) DEFAULT NULL,
  `update_num` int(11) DEFAULT NULL,
  `app_author` char(100) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `app_type` tinyint(4) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `curr_version` char(100) DEFAULT NULL,
  `old_version` char(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_app
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_article`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_article`;
CREATE TABLE `ecm_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `cate_id` int(10) NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `content` text,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `add_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `code` (`code`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_article
-- ----------------------------
INSERT INTO `ecm_article` VALUES ('1', 'eula', '用户服务协议', '3', '0', '', '<p>特别提醒用户认真阅读本《用户服务协议》(下称《协议》) 中各条款。除非您接受本《协议》条款，否则您无权使用本网站提供的相关服务。您的使用行为将视为对本《协议》的接受，并同意接受本《协议》各项条款的约束。 <br /> <br /> <strong>一、定义</strong><br /></p>\r\n<ol>\r\n<li>\"用户\"指符合本协议所规定的条件，同意遵守本网站各种规则、条款（包括但不限于本协议），并使用本网站的个人或机构。</li>\r\n<li>\"卖家\"是指在本网站上出售物品的用户。\"买家\"是指在本网站购买物品的用户。</li>\r\n<li>\"成交\"指买家根据卖家所刊登的交易要求，在特定时间内提出最优的交易条件，因而取得依其提出的条件购买该交易物品的权利。</li>\r\n</ol>\r\n<p><br /> <br /> <strong>二、用户资格</strong><br /> <br /> 只有符合下列条件之一的人员或实体才能申请成为本网站用户，可以使用本网站的服务。</p>\r\n<ol>\r\n<li>年满十八岁，并具有民事权利能力和民事行为能力的自然人；</li>\r\n<li>未满十八岁，但监护人（包括但不仅限于父母）予以书面同意的自然人；</li>\r\n<li>根据中国法律或设立地法律、法规和/或规章成立并合法存在的公司、企事业单位、社团组织和其他组织。</li>\r\n</ol>\r\n<p><br /> 无民事行为能力人、限制民事行为能力人以及无经营或特定经营资格的组织不当注册为本网站用户或超过其民事权利或行为能力范围从事交易的，其与本网站之间的协议自始无效，本网站一经发现，有权立即注销该用户，并追究其使用本网站\"服务\"的一切法律责任。<br /> <br /> <strong>三.用户的权利和义务</strong><br /></p>\r\n<ol>\r\n<li>用户有权根据本协议的规定及本网站发布的相关规则，利用本网站网上交易平台登录物品、发布交易信息、查询物品信息、购买物品、与其他用户订立物品买卖合同、在本网站社区发帖、参加本网站的有关活动及有权享受本网站提供的其他的有关资讯及信息服务。</li>\r\n<li>用户有权根据需要更改密码和交易密码。用户应对以该用户名进行的所有活动和事件负全部责任。</li>\r\n<li>用户有义务确保向本网站提供的任何资料、注册信息真实准确，包括但不限于真实姓名、身份证号、联系电话、地址、邮政编码等。保证本网站及其他用户可以通过上述联系方式与自己进行联系。同时，用户也有义务在相关资料实际变更时及时更新有关注册资料。</li>\r\n<li>用户不得以任何形式擅自转让或授权他人使用自己在本网站的用户帐号。</li>\r\n<li>用户有义务确保在本网站网上交易平台上登录物品、发布的交易信息真实、准确，无误导性。</li>\r\n<li>用户不得在本网站网上交易平台买卖国家禁止销售的或限制销售的物品、不得买卖侵犯他人知识产权或其他合法权益的物品，也不得买卖违背社会公共利益或公共道德的物品。</li>\r\n<li>用户不得在本网站发布各类违法或违规信息。包括但不限于物品信息、交易信息、社区帖子、物品留言，店铺留言，评价内容等。</li>\r\n<li>用户在本网站交易中应当遵守诚实信用原则，不得以干预或操纵物品价格等不正当竞争方式扰乱网上交易秩序，不得从事与网上交易无关的不当行为，不得在交易平台上发布任何违法信息。</li>\r\n<li>用户不应采取不正当手段（包括但不限于虚假交易、互换好评等方式）提高自身或他人信用度，或采用不正当手段恶意评价其他用户，降低其他用户信用度。</li>\r\n<li>用户承诺自己在使用本网站网上交易平台实施的所有行为遵守国家法律、法规和本网站的相关规定以及各种社会公共利益或公共道德。对于任何法律后果的发生，用户将以自己的名义独立承担所有相应的法律责任。</li>\r\n<li>用户在本网站网上交易过程中如与其他用户因交易产生纠纷，可以请求本网站从中予以协调。用户如发现其他用户有违法或违反本协议的行为，可以向本网站举报。如用户因网上交易与其他用户产生诉讼的，用户有权通过司法部门要求本网站提供相关资料。</li>\r\n<li>用户应自行承担因交易产生的相关费用，并依法纳税。</li>\r\n<li>未经本网站书面允许，用户不得将本网站资料以及在交易平台上所展示的任何信息以复制、修改、翻译等形式制作衍生作品、分发或公开展示。</li>\r\n<li>用户同意接收来自本网站的信息，包括但不限于活动信息、交易信息、促销信息等。</li>\r\n</ol>\r\n<p><br /> <br /> <strong>四、 本网站的权利和义务</strong><br /></p>\r\n<ol>\r\n<li>本网站不是传统意义上的\"拍卖商\"，仅为用户提供一个信息交流、进行物品买卖的平台，充当买卖双方之间的交流媒介，而非买主或卖主的代理商、合伙  人、雇员或雇主等经营关系人。公布在本网站上的交易物品是用户自行上传进行交易的物品，并非本网站所有。对于用户刊登物品、提供的信息或参与竞标的过程，  本网站均不加以监视或控制，亦不介入物品的交易过程，包括运送、付款、退款、瑕疵担保及其它交易事项，且不承担因交易物品存在品质、权利上的瑕疵以及交易  方履行交易协议的能力而产生的任何责任，对于出现在拍卖上的物品品质、安全性或合法性，本网站均不予保证。</li>\r\n<li>本网站有义务在现有技术水平的基础上努力确保整个网上交易平台的正常运行，尽力避免服务中断或将中断时间限制在最短时间内，保证用户网上交易活动的顺利进行。</li>\r\n<li>本网站有义务对用户在注册使用本网站网上交易平台中所遇到的问题及反映的情况及时作出回复。 </li>\r\n<li>本网站有权对用户的注册资料进行查阅，对存在任何问题或怀疑的注册资料，本网站有权发出通知询问用户并要求用户做出解释、改正，或直接做出处罚、删除等处理。</li>\r\n<li>用  户因在本网站网上交易与其他用户产生纠纷的，用户通过司法部门或行政部门依照法定程序要求本网站提供相关资料，本网站将积极配合并提供有关资料；用户将纠  纷告知本网站，或本网站知悉纠纷情况的，经审核后，本网站有权通过电子邮件及电话联系向纠纷双方了解纠纷情况，并将所了解的情况通过电子邮件互相通知对  方。 </li>\r\n<li>因网上交易平台的特殊性，本网站没有义务对所有用户的注册资料、所有的交易行为以及与交易有关的其他事项进行事先审查，但如发生以下情形，本网站有权限制用户的活动、向用户核实有关资料、发出警告通知、暂时中止、无限期地中止及拒绝向该用户提供服务：         \r\n<ul>\r\n<li>用户违反本协议或因被提及而纳入本协议的文件；</li>\r\n<li>存在用户或其他第三方通知本网站，认为某个用户或具体交易事项存在违法或不当行为，并提供相关证据，而本网站无法联系到该用户核证或验证该用户向本网站提供的任何资料；</li>\r\n<li>存在用户或其他第三方通知本网站，认为某个用户或具体交易事项存在违法或不当行为，并提供相关证据。本网站以普通非专业交易者的知识水平标准对相关内容进行判别，可以明显认为这些内容或行为可能对本网站用户或本网站造成财务损失或法律责任。 </li>\r\n</ul>\r\n</li>\r\n<li>在反网络欺诈行动中，本着保护广大用户利益的原则，当用户举报自己交易可能存在欺诈而产生交易争议时，本网站有权通过表面判断暂时冻结相关用户账号，并有权核对当事人身份资料及要求提供交易相关证明材料。</li>\r\n<li>根据国家法律法规、本协议的内容和本网站所掌握的事实依据，可以认定用户存在违法或违反本协议行为以及在本网站交易平台上的其他不当行为，本网站有权在本网站交易平台及所在网站上以网络发布形式公布用户的违法行为，并有权随时作出删除相关信息，而无须征得用户的同意。</li>\r\n<li>本  网站有权在不通知用户的前提下删除或采取其他限制性措施处理下列信息：包括但不限于以规避费用为目的；以炒作信用为目的；存在欺诈等恶意或虚假内容；与网  上交易无关或不是以交易为目的；存在恶意竞价或其他试图扰乱正常交易秩序因素；该信息违反公共利益或可能严重损害本网站和其他用户合法利益的。</li>\r\n<li>用  户授予本网站独家的、全球通用的、永久的、免费的信息许可使用权利，本网站有权对该权利进行再授权，依此授权本网站有权(全部或部份地)  使用、复制、修订、改写、发布、翻译、分发、执行和展示用户公示于网站的各类信息或制作其派生作品，以现在已知或日后开发的任何形式、媒体或技术，将上述  信息纳入其他作品内。</li>\r\n</ol>\r\n<p><br /> <br /> <strong>五、服务的中断和终止</strong><br /></p>\r\n<ol>\r\n<li>在  本网站未向用户收取相关服务费用的情况下，本网站可自行全权决定以任何理由  (包括但不限于本网站认为用户已违反本协议的字面意义和精神，或用户在超过180天内未登录本网站等)  终止对用户的服务，并不再保存用户在本网站的全部资料（包括但不限于用户信息、商品信息、交易信息等）。同时本网站可自行全权决定，在发出通知或不发出通  知的情况下，随时停止提供全部或部分服务。服务终止后，本网站没有义务为用户保留原用户资料或与之相关的任何信息，或转发任何未曾阅读或发送的信息给用户  或第三方。此外，本网站不就终止对用户的服务而对用户或任何第三方承担任何责任。 </li>\r\n<li>如用户向本网站提出注销本网站注册用户身份，需经本网站审核同意，由本网站注销该注册用户，用户即解除与本网站的协议关系，但本网站仍保留下列权利：         \r\n<ul>\r\n<li>用户注销后，本网站有权保留该用户的资料,包括但不限于以前的用户资料、店铺资料、商品资料和交易记录等。 </li>\r\n<li>用户注销后，如用户在注销前在本网站交易平台上存在违法行为或违反本协议的行为，本网站仍可行使本协议所规定的权利。 </li>\r\n</ul>\r\n</li>\r\n<li>如存在下列情况，本网站可以通过注销用户的方式终止服务：         \r\n<ul>\r\n<li>在用户违反本协议相关规定时，本网站有权终止向该用户提供服务。本网站将在中断服务时通知用户。但如该用户在被本网站终止提供服务后，再一次直接或间接或以他人名义注册为本网站用户的，本网站有权再次单方面终止为该用户提供服务；</li>\r\n<li>一旦本网站发现用户注册资料中主要内容是虚假的，本网站有权随时终止为该用户提供服务； </li>\r\n<li>本协议终止或更新时，用户未确认新的协议的。 </li>\r\n<li>其它本网站认为需终止服务的情况。 </li>\r\n</ul>\r\n</li>\r\n<li>因用户违反相关法律法规或者违反本协议规定等原因而致使本网站中断、终止对用户服务的，对于服务中断、终止之前用户交易行为依下列原则处理：         \r\n<ul>\r\n<li>本网站有权决定是否在中断、终止对用户服务前将用户被中断或终止服务的情况和原因通知用户交易关系方，包括但不限于对该交易有意向但尚未达成交易的用户,参与该交易竞价的用户，已达成交易要约用户。</li>\r\n<li>服务中断、终止之前，用户已经上传至本网站的物品尚未交易或交易尚未完成的，本网站有权在中断、终止服务的同时删除此项物品的相关信息。 </li>\r\n<li>服务中断、终止之前，用户已经就其他用户出售的具体物品作出要约，但交易尚未结束，本网站有权在中断或终止服务的同时删除该用户的相关要约和信息。</li>\r\n</ul>\r\n</li>\r\n<li>本网站若因用户的行为（包括但不限于刊登的商品、在本网站社区发帖等）侵害了第三方的权利或违反了相关规定，而受到第三方的追偿或受到主管机关的处分时，用户应赔偿本网站因此所产生的一切损失及费用。</li>\r\n<li>对违反相关法律法规或者违反本协议规定，且情节严重的用户，本网站有权终止该用户的其它服务。</li>\r\n</ol>\r\n<p><br /> <br /> <strong>六、协议的修订</strong><br /> <br /> 本协议可由本网站随时修订，并将修订后的协议公告于本网站之上，修订后的条款内容自公告时起生效，并成为本协议的一部分。用户若在本协议修改之后，仍继续使用本网站，则视为用户接受和自愿遵守修订后的协议。本网站行使修改或中断服务时，不需对任何第三方负责。<br /> <br /> <strong>七、 本网站的责任范围 </strong><br /> <br /> 当用户接受该协议时，用户应明确了解并同意∶</p>\r\n<ol>\r\n<li>是否经由本网站下载或取得任何资料，由用户自行考虑、衡量并且自负风险，因下载任何资料而导致用户电脑系统的任何损坏或资料流失，用户应负完全责任。</li>\r\n<li>用户经由本网站取得的建议和资讯，无论其形式或表现，绝不构成本协议未明示规定的任何保证。</li>\r\n<li>基于以下原因而造成的利润、商誉、使用、资料损失或其它无形损失，本网站不承担任何直接、间接、附带、特别、衍生性或惩罚性赔偿（即使本网站已被告知前款赔偿的可能性）：         \r\n<ul>\r\n<li>本网站的使用或无法使用。</li>\r\n<li>经由或通过本网站购买或取得的任何物品，或接收之信息，或进行交易所随之产生的替代物品及服务的购买成本。</li>\r\n<li>用户的传输或资料遭到未获授权的存取或变更。</li>\r\n<li>本网站中任何第三方之声明或行为。</li>\r\n<li>本网站其它相关事宜。</li>\r\n</ul>\r\n</li>\r\n<li>本网站只是为用户提供一个交易的平台，对于用户所刊登的交易物品的合法性、真实性及其品质，以及用户履行交易的能力等，本网站一律不负任何担保责任。用户如果因使用本网站，或因购买刊登于本网站的任何物品，而受有损害时，本网站不负任何补偿或赔偿责任。</li>\r\n<li>本  网站提供与其它互联网上的网站或资源的链接，用户可能会因此连结至其它运营商经营的网站，但不表示本网站与这些运营商有任何关系。其它运营商经营的网站均  由各经营者自行负责，不属于本网站控制及负责范围之内。对于存在或来源于此类网站或资源的任何内容、广告、产品或其它资料，本网站亦不予保证或负责。因使  用或依赖任何此类网站或资源发布的或经由此类网站或资源获得的任何内容、物品或服务所产生的任何损害或损失，本网站不负任何直接或间接的责任。</li>\r\n</ol>\r\n<p><br /> <br /> <strong>八.、不可抗力</strong><br /> <br /> 因不可抗力或者其他意外事件，使得本协议的履行不可能、不必要或者无意义的，双方均不承担责任。本合同所称之不可抗力意指不能预见、不能避免并不能克服的  客观情况，包括但不限于战争、台风、水灾、火灾、雷击或地震、罢工、暴动、法定疾病、黑客攻击、网络病毒、电信部门技术管制、政府行为或任何其它自然或人  为造成的灾难等客观情况。<br /> <br /> <strong>九、争议解决方式</strong><br /></p>\r\n<ol>\r\n<li>本协议及其修订本的有效性、履行和与本协议及其修订本效力有关的所有事宜，将受中华人民共和国法律管辖，任何争议仅适用中华人民共和国法律。</li>\r\n<li>因  使用本网站服务所引起与本网站的任何争议，均应提交深圳仲裁委员会按照该会届时有效的仲裁规则进行仲裁。相关争议应单独仲裁，不得与任何其它方的争议在任  何仲裁中合并处理，该仲裁裁决是终局，对各方均有约束力。如果所涉及的争议不适于仲裁解决，用户同意一切争议由人民法院管辖。</li>\r\n</ol>', '255', '1', '1240122848');
INSERT INTO `ecm_article` VALUES ('2', 'cert_autonym', '什么是实名认证', '3', '0', '', '<p><strong>什么是实名认证？</strong></p>\r\n<p>&ldquo;认证店铺&rdquo;服务是一项对店主身份真实性识别服务。店主可以通过站内PM、电话或管理员EMail的方式 联系并申请该项认证。经过管理员审核确认了店主的真实身份，就可以开通该项认证。</p>\r\n<p>通过该认证，可以说明店主身份的真实有效性，为买家在网络交易的过程中提供一定的信心和保证。</p>\r\n<p><strong>认证申请的方式：</strong></p>\r\n<p>Email：XXXX@XX.com</p>\r\n<p>管理员：XXXXXX</p>', '255', '1', '1240122848');
INSERT INTO `ecm_article` VALUES ('3', 'cert_material', '什么是实体店铺认证', '3', '0', '', '<p><strong>什么是实体店铺认证？</strong></p>\r\n<p>&ldquo;认证店铺&rdquo;服务是一项对店主身份真实性识别服务。店主可以通过站内PM、电话或管理员EMail的方式 联系并申请该项认证。经过管理员审核确认了店主的真实身份，就可以开通该项认证。</p>\r\n<p>通过该认证，可以说明店主身份的真实有效性，为买家在网络交易的过程中提供一定的信心和保证。</p>\r\n<p><strong>认证申请的方式：</strong></p>\r\n<p>Email：XXXX@XX.com</p>\r\n<p>管理员：XXXXXX</p>', '255', '1', '1240122848');
INSERT INTO `ecm_article` VALUES ('4', 'setup_store', '开店协议', '3', '0', '', '<p>使用本公司服务所须遵守的条款和条件。<br /><br />1.用户资格<br />本公司的服务仅向适用法律下能够签订具有法律约束力的合同的个人提供并仅由其使用。在不限制前述规定的前提下，本公司的服务不向18周岁以下或被临时或无限期中止的用户提供。如您不合资格，请勿使用本公司的服务。此外，您的帐户（包括信用评价）和用户名不得向其他方转让或出售。另外，本公司保留根据其意愿中止或终止您的帐户的权利。<br /><br />2.您的资料（包括但不限于所添加的任何商品）不得：<br />*具有欺诈性、虚假、不准确或具误导性；<br />*侵犯任何第三方著作权、专利权、商标权、商业秘密或其他专有权利或发表权或隐私权；<br />*违反任何适用的法律或法规（包括但不限于有关出口管制、消费者保护、不正当竞争、刑法、反歧视或贸易惯例/公平贸易法律的法律或法规）；<br />*有侮辱或者诽谤他人，侵害他人合法权益的内容；<br />*有淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的内容；<br />*包含可能破坏、改变、删除、不利影响、秘密截取、未经授权而接触或征用任何系统、数据或个人资料的任何病毒、特洛依木马、蠕虫、定时炸弹、删除蝇、复活节彩蛋、间谍软件或其他电脑程序；<br /><br />3.违约<br />如发生以下情形，本公司可能限制您的活动、立即删除您的商品、向本公司社区发出有关您的行为的警告、发出警告通知、暂时中止、无限期地中止或终止您的用户资格及拒绝向您提供服务：<br />(a)您违反本协议或纳入本协议的文件；<br />(b)本公司无法核证或验证您向本公司提供的任何资料；<br />(c)本公司相信您的行为可能对您、本公司用户或本公司造成损失或法律责任。<br /><br />4.责任限制<br />本公司、本公司的关联公司和相关实体或本公司的供应商在任何情况下均不就因本公司的网站、本公司的服务或本协议而产生或与之有关的利润损失或任何特别、间接或后果性的损害（无论以何种方式产生，包括疏忽）承担任何责任。您同意您就您自身行为之合法性单独承担责任。您同意，本公司和本公司的所有关联公司和相关实体对本公司用户的行为的合法性及产生的任何结果不承担责任。<br /><br />5.无代理关系<br />用户和本公司是独立的合同方，本协议无意建立也没有创立任何代理、合伙、合营、雇员与雇主或特许经营关系。本公司也不对任何用户及其网上交易行为做出明示或默许的推荐、承诺或担保。<br /><br />6.一般规定<br />本协议在所有方面均受中华人民共和国法律管辖。本协议的规定是可分割的，如本协议任何规定被裁定为无效或不可执行，该规定可被删除而其余条款应予以执行。</p>', '255', '1', '1240122848');
INSERT INTO `ecm_article` VALUES ('5', 'msn_privacy', 'MSN在线通隐私策略', '3', '0', '', '<p>Msn在线通隐私策略旨在说明您在本网站使用Msn在线通功能时我们如何保护您的Msn帐号信息。<br /> 我们认为隐私权非常重要。我们希望此隐私保护中心有助于您在本网站更好使用Msn在线通<br /> <strong>我们收集的信息</strong></p><blockquote>* 您在本网站激活Msn在线通时,程序将会记录您的Msn在线通帐号</blockquote><p><br /> <strong>您的选择</strong></p><blockquote>* 您可以在本网站随时注销您的Msn在线通帐号</blockquote><p><br /> <strong>其他隐私声明</strong></p><blockquote>* 如果我们需要改变本网站Msn在线通的隐私策略, 我们会把相关的改动在此页面发布.</blockquote>', '255', '1', '1240122848');
INSERT INTO `ecm_article` VALUES ('6', '', '购物保障', '-1', '2', null, '<p style=\"margin: 10px; line-height: 150%;\"><strong><span style=\"color: #0000ff;\">一、专业IT产品销售 </span></strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 演示店铺，专业的IT产品销售网站，包括：笔记本电脑、台式机、数码相机、摄像机、MP3、MP4、电脑散件、DIY装 机、手机、通讯设备、办公耗材、配件外设、移动存储、网络产品、各类软件等。有正规可靠的进货渠道，有专业的管理团队，有完善的网络技术平台，有经验丰富 的售后服务人员，绝对值得您信赖。<br /><br /><strong><span style=\"color: #0000ff;\">二、实体店直接供货 </span></strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 演示店铺下设实体店直接供货，将在全国每个城市设立一个实体店，以供当地区供货和售后服务。<br /><br /><strong><span style=\"color: #0000ff;\">三、付款安全</span></strong> <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 用户可以选择财付通付款（中间信用担保）、货到付款（货运公司代收）、银行转帐、在线支付，因为卖家都是实体店商户，所以支付非常安全。<br /><br /><strong><span style=\"color: #0000ff;\">四、完善的售后服务</span></strong> <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 因为所售产品都是正品行货，能在全国联保的商品，都可以全国联保；销售的商品均提供与到实体店购买一样的售后服务保障。</p>\r\n<p>&nbsp;</p>', '1', '1', '1249544249');
INSERT INTO `ecm_article` VALUES ('7', '', '系统升级通知（周二）！', '2', '0', '', '<p><span style=\"font-size: 9pt; color: #666666; font-family: 宋体;\"><strong><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">修改本页内容，请到</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\"> </span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">管理后台</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span> 网站<span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span>&nbsp;文章管理</span></span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\">&nbsp;&nbsp; 找到相关文章</span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">进行编辑</span></strong></span></p>\r\n<p>尊敬的用户，您好！</p>\r\n<p>为了给广大用户提供更好的服务，拟在 2009年 6月 6日 12:30 － 14:00 做系统升级。</p>\r\n<p>届时，页面会出现暂时不能使用的情况。</p>\r\n<p>在此，衷心的感谢每位用户一贯以来对我们工作的支持和关注。</p>', '255', '1', '1249610440');
INSERT INTO `ecm_article` VALUES ('8', '', '8月8日暂停货品出库', '2', '0', '', '<p><span style=\"font-size: 9pt; color: #666666; font-family: 宋体;\"><strong><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">修改本页内容，请到</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\"> </span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">管理后台</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span> 网站<span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span>&nbsp;文章管理</span></span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\">&nbsp;&nbsp; 找到相关文章</span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">进行编辑</span></strong></span></p>\r\n<p><span>敬爱的顾客： <br />我 们将于下2009年8月8日进行内部货品盘点和整合，当天将暂停货品的出库，但是为了广大用户能够尽量在周一收到您周六晚间和周日下午 16：00 前 生成的有效本地订单 (外环外和外地订单将在周日下午发出，具体配送时间根据订单所选的货运方式而定) ，将原有的上海外环线以内的一日二送改为一日一送的配送方式。 8月8日配送时间为上午9:00 至下午 18：00，由此为您带来的不便，还请您谅解。 届时我们将不提供上门自提，售后和送货等其他服务。 从2008年8月8日星期二起外环线以内的配送恢复为一日二送。 在此衷心的感谢各位顾客一贯以来对我们工作的支持和关注。</span></p>', '255', '1', '1249610480');
INSERT INTO `ecm_article` VALUES ('9', '', '商品评论改版升级!', '2', '0', '', '<p><span style=\"font-size: 9pt; color: #666666; font-family: 宋体;\"><strong><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">修改本页内容，请到</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\"> </span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">管理后台</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span> 网站<span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span>&nbsp;文章管理</span></span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\">&nbsp;&nbsp; 找到相关文章</span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">进行编辑</span></strong></span></p>\r\n<p>首先，为了使得大家能更充分参与，我们将逐步放开对产品评论的资格限制， 顾客可以在购买商品后对商品进行评价，其他顾客还可以对评价进行是否好评的参与，得到较多用户好评的评论将得到更多的展示机会。</p>', '255', '1', '1249610514');
INSERT INTO `ecm_article` VALUES ('10', '', '银行系统升级通告！', '2', '0', '', '<p><span style=\"font-size: 9pt; color: #666666; font-family: 宋体;\"><strong><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">修改本页内容，请到</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\"> </span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">管理后台</span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span> 网站<span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\"> <span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">&gt;&gt;</span>&nbsp;文章管理</span></span><span style=\"font-size: 10.5pt; color: red; font-family: \'Times New Roman\';\" lang=\"EN-US\">&nbsp;&nbsp; 找到相关文章</span><span style=\"font-size: 10.5pt; color: red; font-family: 宋体;\">进行编辑</span></strong></span></p>\r\n<p>敬的顾客：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 由于银行系统将于12.10号进行升级，因此在此期间，网上支付业务将会暂停，本网站将会暂停网上支付方式的订单，对此进行通告，谢谢您一贯的支持。</p>', '255', '1', '1249610535');
INSERT INTO `ecm_article` VALUES ('11', '', '顾客必读', '4', '0', '', '<h4><br /></h4>\r\n<h4>如何订购商品？</h4>\r\n<p>您可以首先浏览我们的网站了解商品。看到您满意的商品您可以直接在我们的网站上实现订购。您也可以和我们网站的客服人员联系订购。</p>\r\n<h4>我通过网站看到你们的商品后觉得不错，但是我不是经常上网，你可以寄一些商品的图片和介绍给我吗？</h4>\r\n<p>答：我们的网站会不定期地为会员发送商品信息资料的电子邮件。如果您目前还没有成为我们的会员，您可以直接在我们的网站上注册，非常方便。</p>\r\n<h4>请告诉我在这里购物的理由好吗？</h4>\r\n<ol>\r\n<li>我们是一家拥有长期经营零售业务经验的网站；</li>\r\n<li>我们将给您带来优质的商品及更优惠的价格；</li>\r\n<li>多种付款方式以及快速的全国配送；</li>\r\n<li>人性化的退换货事宜；</li>\r\n<li>体贴入微的会员积分计划；</li>\r\n<li>所有产品为原厂正规包装；</li>\r\n</ol>\r\n<h4>你们的商品我都非常喜欢，已经购买了很多，但是有些怎么一直没货？会不会订不到？</h4>\r\n<p>由于网站顾客购买量比较大，商品随时可能断货，您可以通过网站上的&ldquo;到货通知&rdquo;按钮预定商品或直接联系我们的网站客服进行预约订购。</p>\r\n<h4>所有的产品都能够在网站上购买?</h4>\r\n<p>答：目前网站查找的都是可以订购的，但是必须是仓库中有库存的产品我们才可以与您确认。部分热销产品也可以通过我们的网站做一个预约，等到货品到后，我们会立即通过电话或者电子邮件的方式通知您来订购。</p>\r\n<h4>为什么要注册会员？</h4>\r\n<ol>\r\n<li>只有注册用户才可以在网上进行订购，享受优惠的价格。</li>\r\n<li>只有注册用户才可以登录\"会员中心\"，使用更多的会员购物功能,管理自己的资料。</li>\r\n<li>只有注册用户才可以在网上给其他注册的朋友留言。</li>\r\n<li>只有注册用户才有可能获取我们赠送的礼品。</li>\r\n</ol>\r\n<h4>忘记了密码怎么办？</h4>\r\n<p>为了保护客户利益，我们无法看到您的密码。当您忘记密码时，请登录注册页面，点击\"忘记密码\"，系统会自动将您的密码通过email告诉您，您可以登录\"会员中心\"去更改密码，以确保您的利益。</p>\r\n<h4>积分是怎么回事？有什么作用？</h4>\r\n<p>积分的高低只反映您对我们的关注和支持程度。我们的积分是通过订购商品产生的。对于高积分的客户我们会有一定的奖励，如积分兑换商品、积分抵扣价格、赠送商品,更优惠的价格购买商品等，以此回馈广大顾客。</p>', '255', '1', '1249614530');
INSERT INTO `ecm_article` VALUES ('12', '', '商品退货保障', '4', '0', '', '<h4><br /></h4>\r\n<h4>符合以下条件，可以要求换货</h4>\r\n<ol>\r\n<li>客户在收到货物时当面在送货员面前拆包检查，发现货物有质量问题的；</li>\r\n<li>实际收到货物与网站上描述的有很大的出入的。</li>\r\n</ol>\r\n<p><strong>换货流程</strong>：客户当面要求送货人员退回货物，然后与我们联系。我们会为您重新发货，货物到达时间顺延。</p>\r\n<h4>符合以下条件，可以要求退货</h4>\r\n<ol>\r\n<li>客户收到货物后两天之内，发现商品有明显的制造缺陷的；</li>\r\n<li>货物经过一次换货但仍然存在质量问题的；</li>\r\n<li>由于人为原因造成超过我们承诺到货之日5天还没收到货物的。</li>\r\n</ol>\r\n<p><strong>退货流程：</strong>客户在收到货物后两天内与我们联系，我们会在三个工作日内通过银行汇款把您的货款退回。</p>\r\n<h4>在以下情况我们有权拒绝客户的退换货要求</h4>\r\n<ol>\r\n<li>货物出现破损，但没有在收货时当场要求送货人员换货的；</li>\r\n<li>超过退换货期限的退换货要求；</li>\r\n<li>退换货物不全或者外观受损 ；</li>\r\n<li>客户发货单据丢失或者不全；</li>\r\n<li>产品并非我们提供；</li>\r\n<li>货物本身不存在质量问题的 。</li>\r\n</ol>', '255', '1', '1249614660');
INSERT INTO `ecm_article` VALUES ('13', '', '体贴的售后服务', '5', '0', '', '<p>&nbsp;</p>\r\n<p>本网站所售产品均实行三包政策，请顾客保存好有效凭证，以确保我们为您更好服务。本公司的客户除享受国家规定&ldquo;三包&rdquo;。您可以更放心地在这里购物。<br /></p>\r\n<h3>保修细则</h3>\r\n<h4>一、在本网站购买的商品，自购买日起(以到货登记为准)7日内出现性能故障，您可以选择退货、换货或修理。</h4>\r\n<ol>\r\n<li>在接到您的产品后，我公司将问题商品送厂商特约维修中心检测； </li>\r\n<li>检测报出来后，如非人为损坏的，是产品本身质量问题，我公司会及时按您的要求予以退款、换可或维修。 </li>\r\n<li>如果检测结果是无故障或是人为因素造成的故障，我公司会及时通知您，并咨询您的处理意见。 </li>\r\n</ol>\r\n<h4>二、在本公司购买的商品，自购日起(以到货登记为准)15日内出现性能故障，您可以选择换货或修理。(享受15天退换货无需理由的商品，按《15天退换货无需理由细则》办理)</h4>\r\n<ol>\r\n<li>在接到您的产品后，我公司将问题商品送厂商特约维修中心检测； </li>\r\n<li>检测报出来后，如非人为损坏的，是产品本身质量问题，我公司会及时按您的要求予以退款、换可或维修。 </li>\r\n<li>如果检测结果是无故障或是人为因素造成的故障，我公司会及时通知您，并咨询您的处理意见。</li>\r\n</ol>\r\n<h4>三、在本公司购买的商品，自购日起(以到货登记为准)一年之内出现非人为损坏的质量问题，本公司承诺免费保修。</h4>\r\n<ol>\r\n<li>在接到您的产品后，我公司将问题商品送厂商特约维修中心检测； </li>\r\n<li>检测报出来后，如非人为损坏的，是产品本身质量问题，我公司会及时按您的要求予以退款、换可或维修。 </li>\r\n<li>如果检测结果是无故障或是人为因素造成的故障，我公司会及时通知您，并咨询您的处理意见。 </li>\r\n</ol>\r\n<h3>收费维修：</h3>\r\n<h4>一、对于人为造成的故障，本公司将采取收费维修，包括：</h4>\r\n<ol>\r\n<li>产品内部被私自拆开或其中任何部分被更替； </li>\r\n<li>商品里面的条码不清楚，无法成功判断； </li>\r\n<li>有入水、碎裂、损毁或有腐蚀等现象； </li>\r\n<li>过了保修期的商品。</li>\r\n</ol>\r\n<h4>二、符合以下条件，可以要求换货：</h4>\r\n<ol>\r\n<li>客户在收到货物时当面在送货员面前拆包检查，发现货物有质量问题的 </li>\r\n<li>实际收到货物与网站上描述的有很大的出入的 </li>\r\n<li>换货流程：客户当面要求送货人员退回货物，然后与我们联系。我们会在一个工作日内为您重新发货，货物到达时间顺延。</li>\r\n</ol>\r\n<h4>三、符合以下条件，可以要求退货：</h4>\r\n<p>客户收到货物后两天之内，</p>\r\n<ol>\r\n<li>发现商品有明显的制造缺陷的 </li>\r\n<li>货物经过一次换货但仍然存在质量问题的 </li>\r\n<li>由于人为原因造成超过我们承诺到货之日三天还没收到货物的</li>\r\n</ol>\r\n<p>退货流程：客户在收到货物后两天内与我们联系，我们会在两个工作日内通过银行汇款把您的货款退回。</p>\r\n<h4>在以下情况我们有权拒绝客户的退换货要求：</h4>\r\n<ol>\r\n<li>货物出现破损，但没有在收货时当场要求送货人员换货的 </li>\r\n<li>超过退换货期限的退换货要求 </li>\r\n<li>退换货物不全或者外观受损 </li>\r\n<li>客户发货单据丢失或者不全 </li>\r\n<li>产品并非我们提供 </li>\r\n<li>货物本身不存在质量问题的</li>\r\n</ol>', '255', '1', '1249614760');
INSERT INTO `ecm_article` VALUES ('14', '', '免责条款', '5', '0', '', '<p>&nbsp;</p>\r\n<h4>免责声明</h4>\r\n<p>如因不可抗力或其他无法控制的原因造成网站销售系统崩溃或无法正常使用，从而导致网上交易无法完成或丢失有关的信息、记录等，网站将不承担责任。但是我们将会尽合理的可能协助处理善后事宜，并努力使客户减少可能遭受的经济损失。<br />本 店可以按买方的要求代办相关运输手续，但我们的责任义务仅限于按时发货，遇到物流（邮政）意外时协助买方查询，不承担任何物流（邮政）提供给顾客之外的赔 偿，一切查询索赔事宜均按照物流（邮政）的规定办理。在物流（邮政）全程查询期限未满之前，买方不得要求赔偿。提醒买方一定核实好收货详细地址和收货人电 话，以免耽误投递。凡在本店购物，均视为如同意此声明。</p>\r\n<h4>客户监督</h4>\r\n<p>我们希望通过不懈努力，为客户提供最佳服务，我们在给客户提供服务的全程中接受客户的监督。</p>\r\n<h4>争议处理</h4>\r\n<p>如果客户与网站之间发生任何争议，可依据当时双方所认定的协议或相关法律来解决。</p>', '255', '1', '1249614798');
INSERT INTO `ecm_article` VALUES ('15', '', '简单的购物流程', '5', '0', '', '<h4><br /></h4>\r\n<h4>怎样注册？</h4>\r\n<p>答：您可以直接点击\"会员注册\"进行注册。注册很简单，您只需按注册向导的要求输入一些基本信息即可。为了准确地为您服务，请务必在注册时填写您的真实信息，我们会为您保密。输入的帐号要4-10位，仅可使用英文字母、数字\"-\"。</p>\r\n<h4>怎样成为会员?</h4>\r\n<p>答：您可以直接点击\"会员登录与注册\"进行注册。注册很简单，您只需根据系统提示输入相关资料即可，请您填写完毕时，务必核对填写内容的准确性，并谨记您的会员账号和密码，以便您查询订单或是希望网站提供予您更多的服务时用以核对您的身份。</p>\r\n<h4>如何在网上下单购买，怎么一个操作流程呢？</h4>\r\n<p>答：这种方式和您逛商场的方式十分相似，您只要按照我们的商品分类页面或进入\"钻石珠宝\"、\"个性定制\"等逐页按照连接指明的路径浏览就可以了。 一旦看中了您喜欢的商品，您可以随时点击\"放入购物篮\"按钮将它放入\"购物篮\"。随后，您可以按\"去收银台\"。我们的商品十分丰富，不过您别担心，我们在 每一页中都设立了详细明白的导航条，您是不会迷路的。</p>', '255', '1', '1249614826');

-- ----------------------------
-- Table structure for `ecm_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_attribute`;
CREATE TABLE `ecm_attribute` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `input_mode` varchar(10) NOT NULL DEFAULT 'text',
  `def_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_bank`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_bank`;
CREATE TABLE `ecm_bank` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `card_number` char(32) NOT NULL,
  `cardholder` char(32) NOT NULL,
  `bank_name` char(64) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_bank
-- ----------------------------
INSERT INTO `ecm_bank` VALUES ('1', '2', '6222222222222222222', '张先生', '中国工商银行', '四川成都', '1467761397');
INSERT INTO `ecm_bank` VALUES ('2', '2', '15900000000', '张先生', '支付宝', '', '1467761430');
INSERT INTO `ecm_bank` VALUES ('3', '2', '6242222222222222222', '张先生', '中国建设银行', '四川成都', '1467765602');
INSERT INTO `ecm_bank` VALUES ('4', '2', '222222222222222', '张先生', '中国银行', '四川成都', '1467765641');
INSERT INTO `ecm_bank` VALUES ('5', '21', '6217003812345698147', '夏天', '中国建设银行', '成都市锦江区上东花园', '1469668668');
INSERT INTO `ecm_bank` VALUES ('9', '3', '123456789441255', 'dsafsd', '中国工商银行', '', '1470866400');

-- ----------------------------
-- Table structure for `ecm_brand`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_brand`;
CREATE TABLE `ecm_brand` (
  `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) NOT NULL DEFAULT '',
  `brand_logo` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `if_show` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `tag` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`brand_id`),
  KEY `tag` (`tag`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_brand
-- ----------------------------
INSERT INTO `ecm_brand` VALUES ('1', '麦包包', 'data/files/mall/brand/1.gif', '1', '1', '0', '1', '男女包');
INSERT INTO `ecm_brand` VALUES ('2', 'ESprit', 'data/files/mall/brand/2.jpg', '2', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('3', '李宁', 'data/files/mall/brand/3.jpg', '3', '1', '0', '1', '运动服');
INSERT INTO `ecm_brand` VALUES ('4', 'G-Star', 'data/files/mall/brand/4.jpg', '4', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('5', 'Lee', 'data/files/mall/brand/5.jpg', '5', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('6', 'Jack & Jones', 'data/files/mall/brand/6.jpg', '6', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('7', 'DIOR', 'data/files/mall/brand/7.jpg', '6', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('8', 'Chanel', 'data/files/mall/brand/8.jpg', '7', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('9', 'PUMA', 'data/files/mall/brand/9.jpg', '8', '1', '0', '1', '');
INSERT INTO `ecm_brand` VALUES ('10', '美特斯邦威', 'data/files/mall/brand/10.jpg', '9', '1', '0', '1', '服装');
INSERT INTO `ecm_brand` VALUES ('11', 'Adidas', 'data/files/mall/brand/11.jpg', '10', '1', '0', '1', '运动服');
INSERT INTO `ecm_brand` VALUES ('12', 'Nike', 'data/files/mall/brand/12.jpg', '11', '1', '0', '1', '服装');
INSERT INTO `ecm_brand` VALUES ('13', '欧莱雅', 'data/files/mall/brand/13.jpg', '255', '1', '0', '1', '美容美妆');

-- ----------------------------
-- Table structure for `ecm_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_cart`;
CREATE TABLE `ecm_cart` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `spec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `specification` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `goods_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_cart
-- ----------------------------
INSERT INTO `ecm_cart` VALUES ('40', '5', 'a31b2df138e43b094afe3cda5bddfc1d', '2', '29', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '74', '', '328.00', '1', 'data/files/store_2/goods_121/small_200908061008412008.jpg');
INSERT INTO `ecm_cart` VALUES ('26', '6', '0194e886a234b76776d42c84deb7675d', '2', '10', '家居横纹休闲长裙', '26', '颜色:蓝色 尺码:S', '170.00', '1', 'data/files/store_2/goods_69/small_200908060914291406.jpg');
INSERT INTO `ecm_cart` VALUES ('39', '4', '', '5', '30', '代购韩国SZ2014冬装中长款羽绒服休闲气质女装加厚时尚女外套', '75', '颜色:红色 尺码:M', '499.00', '1', 'data/files/store_5/goods_100/small_201501130651402344.png');

-- ----------------------------
-- Table structure for `ecm_category_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_category_goods`;
CREATE TABLE `ecm_category_goods` (
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cate_id`,`goods_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_category_goods
-- ----------------------------
INSERT INTO `ecm_category_goods` VALUES ('1201', '25');
INSERT INTO `ecm_category_goods` VALUES ('1201', '28');
INSERT INTO `ecm_category_goods` VALUES ('1201', '29');
INSERT INTO `ecm_category_goods` VALUES ('1202', '4');
INSERT INTO `ecm_category_goods` VALUES ('1202', '6');
INSERT INTO `ecm_category_goods` VALUES ('1202', '10');
INSERT INTO `ecm_category_goods` VALUES ('1202', '13');
INSERT INTO `ecm_category_goods` VALUES ('1202', '18');
INSERT INTO `ecm_category_goods` VALUES ('1202', '19');
INSERT INTO `ecm_category_goods` VALUES ('1202', '21');
INSERT INTO `ecm_category_goods` VALUES ('1202', '23');
INSERT INTO `ecm_category_goods` VALUES ('1203', '1');
INSERT INTO `ecm_category_goods` VALUES ('1203', '3');
INSERT INTO `ecm_category_goods` VALUES ('1203', '7');
INSERT INTO `ecm_category_goods` VALUES ('1203', '9');
INSERT INTO `ecm_category_goods` VALUES ('1203', '14');
INSERT INTO `ecm_category_goods` VALUES ('1203', '17');
INSERT INTO `ecm_category_goods` VALUES ('1203', '20');
INSERT INTO `ecm_category_goods` VALUES ('1203', '22');
INSERT INTO `ecm_category_goods` VALUES ('1203', '26');
INSERT INTO `ecm_category_goods` VALUES ('1203', '27');
INSERT INTO `ecm_category_goods` VALUES ('1209', '2');
INSERT INTO `ecm_category_goods` VALUES ('1209', '5');
INSERT INTO `ecm_category_goods` VALUES ('1209', '8');
INSERT INTO `ecm_category_goods` VALUES ('1209', '11');
INSERT INTO `ecm_category_goods` VALUES ('1209', '12');
INSERT INTO `ecm_category_goods` VALUES ('1209', '15');
INSERT INTO `ecm_category_goods` VALUES ('1209', '16');
INSERT INTO `ecm_category_goods` VALUES ('1209', '24');

-- ----------------------------
-- Table structure for `ecm_category_store`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_category_store`;
CREATE TABLE `ecm_category_store` (
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cate_id`,`store_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_category_store
-- ----------------------------
INSERT INTO `ecm_category_store` VALUES ('2', '2');
INSERT INTO `ecm_category_store` VALUES ('2', '21');

-- ----------------------------
-- Table structure for `ecm_cate_pvs`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_cate_pvs`;
CREATE TABLE `ecm_cate_pvs` (
  `cate_id` int(11) NOT NULL,
  `pvs` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_cate_pvs
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_collect`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_collect`;
CREATE TABLE `ecm_collect` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'goods',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(60) DEFAULT NULL,
  `add_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`,`type`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_collect
-- ----------------------------
INSERT INTO `ecm_collect` VALUES ('3', 'goods', '3', '', '1421825705');
INSERT INTO `ecm_collect` VALUES ('3', 'goods', '16', '', '1469745367');
INSERT INTO `ecm_collect` VALUES ('3', 'goods', '25', '', '1470937825');
INSERT INTO `ecm_collect` VALUES ('3', 'goods', '26', '', '1470937824');
INSERT INTO `ecm_collect` VALUES ('3', 'goods', '39', '', '1470937825');

-- ----------------------------
-- Table structure for `ecm_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_coupon`;
CREATE TABLE `ecm_coupon` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_name` varchar(100) NOT NULL DEFAULT '',
  `coupon_value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `use_times` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `min_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `if_issue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`coupon_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_coupon
-- ----------------------------
INSERT INTO `ecm_coupon` VALUES ('1', '21', '10元抵扣优惠券', '10.00', '1', '1469692800', '1470038399', '100.00', '1');
INSERT INTO `ecm_coupon` VALUES ('2', '2', 'yhj-test', '20.00', '100', '1470556800', '1472716799', '20.00', '1');

-- ----------------------------
-- Table structure for `ecm_coupon_sn`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_coupon_sn`;
CREATE TABLE `ecm_coupon_sn` (
  `coupon_sn` varchar(20) NOT NULL,
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0',
  `remain_times` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`coupon_sn`),
  KEY `coupon_id` (`coupon_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_coupon_sn
-- ----------------------------
INSERT INTO `ecm_coupon_sn` VALUES ('000000053221', '1', '1');

-- ----------------------------
-- Table structure for `ecm_delivery_template`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_delivery_template`;
CREATE TABLE `ecm_delivery_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `store_id` int(10) NOT NULL,
  `template_types` text NOT NULL,
  `template_dests` text NOT NULL,
  `template_start_standards` text NOT NULL,
  `template_start_fees` text NOT NULL,
  `template_add_standards` text NOT NULL,
  `template_add_fees` text NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_delivery_template
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_discus`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_discus`;
CREATE TABLE `ecm_discus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `buyer_type` char(20) DEFAULT NULL,
  `buyer_remark` varchar(250) DEFAULT NULL,
  `buyer_addtime` int(11) DEFAULT NULL,
  `seller_type` char(20) DEFAULT NULL,
  `seller_remark` varchar(250) DEFAULT NULL,
  `seller_addtime` int(11) DEFAULT NULL,
  `admin_remark` varchar(250) DEFAULT NULL,
  `admin_addtime` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `buyer_name` char(200) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `seller_name` char(200) DEFAULT NULL,
  `is_pay` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_discus
-- ----------------------------
INSERT INTO `ecm_discus` VALUES ('1', '18', null, 'no_recive', 'ssssedddddddddddddddddd', '1470878963', 'all', 'aaaaaaaaaaaaaaaaaaaaa', '1470881103', null, null, '2', '3', 'buyer', '2', '演示店铺', null);

-- ----------------------------
-- Table structure for `ecm_friend`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_friend`;
CREATE TABLE `ecm_friend` (
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `friend_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`owner_id`,`friend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_friend
-- ----------------------------
INSERT INTO `ecm_friend` VALUES ('3', '2', '1249545996');
INSERT INTO `ecm_friend` VALUES ('21', '22', '1469668190');

-- ----------------------------
-- Table structure for `ecm_function`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_function`;
CREATE TABLE `ecm_function` (
  `func_code` varchar(20) NOT NULL DEFAULT '',
  `func_name` varchar(60) NOT NULL DEFAULT '',
  `privileges` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`func_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_function
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_gcategory`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_gcategory`;
CREATE TABLE `ecm_gcategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `logo` char(250) DEFAULT NULL,
  PRIMARY KEY (`cate_id`),
  KEY `store_id` (`store_id`,`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1254 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_gcategory
-- ----------------------------
INSERT INTO `ecm_gcategory` VALUES ('1', '0', '男装', '0', '255', '1', '../data/files/mall/common/1/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('2', '0', 'T恤', '1', '255', '1', '../data/files/mall/common/2/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('3', '0', 'Polo衫', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('4', '0', '卫衣', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('5', '0', '衬衫', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('6', '0', '牛仔裤', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('7', '0', '休闲裤', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('8', '0', '西裤', '14', '255', '1', '');
INSERT INTO `ecm_gcategory` VALUES ('9', '0', '皮裤', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('10', '0', '风衣', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('11', '0', '棉衣', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('12', '0', '皮衣', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('13', '0', '羽绒服', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('14', '0', '西服', '1', '255', '1', '../data/files/mall/common/14/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('15', '0', '夹克', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('16', '0', '西服套装', '14', '255', '1', '');
INSERT INTO `ecm_gcategory` VALUES ('17', '0', '背心', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('18', '0', '毛衣', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('19', '0', '民族服装', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('20', '0', '工装制服', '1', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('21', '0', '女装/女士精品', '0', '255', '1', '../data/files/mall/common/21/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('22', '0', '风衣/长大衣', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('23', '0', '羽绒服', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('24', '0', '棉衣/棉服', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('25', '0', '毛衣', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('26', '0', '超短外套', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('27', '0', '针织衫', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('28', '0', 'T恤', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('29', '0', '卫衣/绒衫', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('30', '0', '半身裙', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('31', '0', '小西装', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('32', '0', '裤子', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('33', '0', '衬衫', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('34', '0', '短外套', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('35', '0', '中老年服装', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('36', '0', '连衣裙', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('37', '0', '牛仔裤', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('38', '0', '蕾丝衫/雪纺衫', '21', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('57', '0', '男女内衣/家居服', '1246', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('58', '0', '文胸', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('59', '0', '文胸套装', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('60', '0', '女士内裤', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('61', '0', '男士内裤', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('62', '0', '塑身内衣', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('63', '0', '保暖内衣', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('64', '0', '睡衣', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('65', '0', '吊带/背心', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('66', '0', '情侣内衣', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('67', '0', '隐形胸罩', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('68', '0', '抹胸', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('69', '0', '袜子', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('70', '0', '肚兜', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('71', '0', '胸垫/胸贴/肩带/吊袜带', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('72', '0', '其它内衣款式', '57', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('73', '0', '运动/颈环配件', '0', '255', '1', '../data/files/mall/common/73/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('74', '0', 'T恤', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('75', '0', '长袖休闲T恤', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('76', '0', '长袖排汗T恤', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('77', '0', '短袖休闲T恤', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('78', '0', '短袖排汗T恤', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('79', '0', '背心/无袖', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('80', '0', 'POLO衫', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('81', '0', '吊带', '74', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('82', '0', '运动套装', '73', '255', '1', '../data/files/mall/common/82/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('83', '0', '冬季套装', '82', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('84', '0', '夏季套装', '82', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('85', '0', '春秋套装', '82', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('86', '0', '外套', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('87', '0', '卫衣', '86', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('88', '0', '风衣', '86', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('89', '0', '绒衣', '86', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('90', '0', '棉衣', '86', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('91', '0', '羽绒衣', '86', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('92', '0', '毛衣/针织', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('93', '0', '运动裤/裙', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('94', '0', '长裤', '93', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('95', '0', '中裤', '93', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('96', '0', '短裤', '93', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('97', '0', '七分/九分裤', '93', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('98', '0', '运动裙', '93', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('99', '0', '马甲', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('100', '0', '夹克', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('101', '0', '运动配件', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('102', '0', '运动袜', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('103', '0', '运动眼镜', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('104', '0', '运动手套', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('105', '0', '运动手表', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('106', '0', '运动水壶', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('107', '0', '运动毛巾', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('108', '0', '其他运动配件', '101', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('109', '0', '运动包袋', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('110', '0', '单肩包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('111', '0', '旅行包/箱', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('112', '0', '钱包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('113', '0', '手提包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('114', '0', '双肩包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('115', '0', '桶包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('116', '0', '腰包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('117', '0', '其他运动包', '109', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('118', '0', '运动颈环/手环', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('119', '0', '运动护具', '73', '255', '1', '../data/files/mall/common/119/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('120', '0', '头带', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('121', '0', '护腕', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('122', '0', '护肘', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('123', '0', '护膝', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('124', '0', '护腿板', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('125', '0', '护踝', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('126', '0', '手套', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('127', '0', '头盔', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('128', '0', '护头', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('129', '0', '护肩', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('130', '0', '护手', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('131', '0', '护腰', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('132', '0', '护具套件', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('133', '0', '其它', '119', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('134', '0', '服饰配件/帽子/围巾', '1243', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('135', '0', '皮带', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('136', '0', '腰带/腰链/腰饰', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('137', '0', '帽子', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('138', '0', '围巾/丝巾/披肩', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('139', '0', '领带', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('140', '0', '领结', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('141', '0', '领带夹', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('142', '0', '头巾', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('143', '0', '袖扣', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('144', '0', '背带', '134', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('162', '0', '流行男鞋', '0', '255', '1', '../data/files/mall/common/162/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('163', '0', '休闲鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('164', '0', '皮鞋', '162', '255', '1', '../data/files/mall/common/164/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('165', '0', '靴子', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('166', '0', '帆布鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('167', '0', '凉鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('168', '0', '凉拖', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('169', '0', '增高鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('170', '0', '功能鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('171', '0', '编织鞋/布鞋/手工鞋', '162', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('172', '0', '女鞋', '0', '255', '1', '../data/files/mall/common/172/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('173', '0', '单鞋(露脚背)', '172', '255', '1', '../data/files/mall/common/173/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('174', '0', '靴子', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('175', '0', '雪地靴', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('176', '0', '凉鞋', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('177', '0', '凉拖', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('178', '0', '休闲球鞋(不露脚背)', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('179', '0', '休闲皮鞋(不露脚背)', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('180', '0', '帆布鞋', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('181', '0', '雨鞋/靴', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('182', '0', '绣花鞋', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('183', '0', '布鞋/手工鞋', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('184', '0', '内增高鞋', '172', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('185', '0', '运动鞋', '73', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('186', '0', '篮球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('187', '0', '跑步鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('188', '0', '足球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('189', '0', '网球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('190', '0', '羽毛球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('191', '0', '全能鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('192', '0', '经典收藏鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('193', '0', '休闲鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('194', '0', '复古鞋/板鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('195', '0', '攀岩', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('196', '0', '运动凉鞋/沙滩鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('197', '0', '排球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('198', '0', '帆布鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('199', '0', '乒乓球鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('200', '0', '溜冰鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('201', '0', '情侣休闲鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('202', '0', '训练鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('203', '0', '运动拖鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('204', '0', '专业健身鞋', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('205', '0', '其它', '185', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('206', '0', '箱包皮具/女包/男包', '0', '255', '1', '../data/files/mall/common/206/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('207', '0', '女用单肩包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('208', '0', '女用斜挎包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('209', '0', '女用多功能包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('210', '0', '手提包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('211', '0', '男用单肩包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('212', '0', '男用手包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('213', '0', '男用多功能包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('214', '0', '钱包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('215', '0', '双肩背包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('216', '0', '旅行包/拉杆', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('217', '0', '腰包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('218', '0', '胸包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('219', '0', '文件包/公文', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('220', '0', '配件小包/女用手包/硬币包', '206', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('221', '0', '品牌手表/流行手表', '1243', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('222', '0', '男表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('223', '0', '女表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('224', '0', '中性表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('225', '0', '对表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('226', '0', '怀表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('227', '0', '古董表/收藏表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('228', '0', '其他手表', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('229', '0', '手表配件', '221', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('230', '0', 'zippo/瑞士军刀/眼镜', '0', '255', '1', '../data/files/mall/common/230/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('231', '0', 'ZIPPO/芝宝', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('232', '0', '品牌打火机/其它打火机', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('233', '0', '瑞士军刀', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('234', '0', '礼品刀具', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('235', '0', '眼镜架', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('236', '0', '眼镜片', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('237', '0', '框架眼镜', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('238', '0', '太阳眼镜', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('239', '0', '功能眼镜', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('240', '0', '游泳镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('241', '0', '潜水镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('242', '0', '司机镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('243', '0', '滑雪镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('244', '0', '电脑护目镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('245', '0', '夜视镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('246', '0', '近视镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('247', '0', '老花镜', '239', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('248', '0', '眼镜配件、护理剂', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('249', '0', '镜盒/镜袋/镜套', '248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('250', '0', '镜布', '248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('251', '0', '隐形眼镜伴侣盒', '248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('252', '0', '隐形眼镜清洁器', '248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('253', '0', '眼镜护理剂', '248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('254', '0', '滴眼液、护眼用品', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('255', '0', '烟具', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('256', '0', '烟斗', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('257', '0', '烟嘴', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('258', '0', '烟斗架', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('259', '0', '烟盒', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('260', '0', '烟斗相关配件', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('261', '0', '压棒及相关工具', '260', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('262', '0', '烟刀', '260', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('263', '0', '烟斗包', '260', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('264', '0', '烟斗清洁用品', '260', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('265', '0', '通条', '260', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('266', '0', '戒烟产品', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('267', '0', '雪茄剪/刀', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('268', '0', '火柴', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('269', '0', '卷烟器', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('270', '0', '其它', '255', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('271', '0', '酒具', '230', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('272', '0', '饰品/流行首饰/时尚饰品', '1243', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('273', '0', '项链/项坠', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('274', '0', '手链/手镯/脚链', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('275', '0', '戒指/指环', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('276', '0', '耳饰', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('277', '0', '胸针/领针', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('278', '0', '情侣对', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('279', '0', '首饰套装', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('280', '0', '发饰', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('281', '0', '摆件', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('282', '0', '裸石/半成品', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('283', '0', '体环', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('284', '0', 'DIY饰品/配件/散珠', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('285', '0', '首饰保养/鉴定用品/首饰盒', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('286', '0', '其它款式', '272', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1200', '2', '韩版女装', '0', '1', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1201', '2', '外套', '1200', '1', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1202', '2', '长裙', '1200', '2', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1203', '2', '女裤', '1200', '3', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1204', '2', '包包', '0', '2', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1205', '2', '手提包', '1204', '1', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1206', '2', '皮夹钱包', '1204', '2', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1207', '2', '时尚女鞋', '0', '3', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1208', '2', '气质单鞋', '1207', '1', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1209', '2', '运动休闲', '1207', '2', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1253', '21', '富安娜1', '1252', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1252', '21', '富安娜', '0', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1237', '0', '美容美妆', '0', '255', '1', '../data/files/mall/common/1237/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('1238', '0', '护肤', '1237', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1239', '0', '彩妆', '1237', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1240', '0', '母婴', '0', '255', '1', '../data/files/mall/common/1240/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('1241', '0', '奶粉', '1240', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1242', '0', '手机数码', '0', '255', '1', '../data/files/mall/common/1242/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('1243', '0', '男女饰品', '0', '255', '1', '../data/files/mall/common/1243/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('1244', '0', '手机', '1242', '255', '1', '../data/files/mall/common/1244/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('1245', '0', '笔记本', '1242', '255', '1', '../data/files/mall/common/1245/logo.jpg');
INSERT INTO `ecm_gcategory` VALUES ('1246', '0', '家居家纺', '0', '255', '1', '../data/files/mall/common/1246/logo.png');
INSERT INTO `ecm_gcategory` VALUES ('1247', '0', '床上四件套', '1246', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1248', '0', '家庭装饰', '1246', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1249', '0', '门', '1248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1250', '0', '衣柜', '1248', '255', '1', null);
INSERT INTO `ecm_gcategory` VALUES ('1251', '0', '书桌', '1248', '255', '1', null);

-- ----------------------------
-- Table structure for `ecm_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods`;
CREATE TABLE `ecm_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'material',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_name` varchar(255) NOT NULL DEFAULT '',
  `brand` varchar(100) NOT NULL,
  `spec_qty` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `spec_name_1` varchar(60) NOT NULL DEFAULT '',
  `spec_name_2` varchar(60) NOT NULL DEFAULT '',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `closed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `close_reason` varchar(255) DEFAULT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `default_spec` int(11) unsigned NOT NULL DEFAULT '0',
  `default_image` varchar(255) NOT NULL DEFAULT '',
  `recommended` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cate_id_1` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_2` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_3` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_4` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tags` varchar(102) NOT NULL,
  `market_price` decimal(10,2) DEFAULT NULL,
  `delivery_template_id` int(11) NOT NULL,
  PRIMARY KEY (`goods_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE,
  KEY `cate_id_1` (`cate_id_1`) USING BTREE,
  KEY `cate_id_2` (`cate_id_2`) USING BTREE,
  KEY `cate_id_3` (`cate_id_3`) USING BTREE,
  KEY `cate_id_4` (`cate_id_4`) USING BTREE,
  KEY `brand` (`brand`(10)) USING BTREE,
  KEY `tags` (`tags`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods
-- ----------------------------
INSERT INTO `ecm_goods` VALUES ('1', '2', 'material', '多彩人生多彩裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/35a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/35b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/35c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/35d.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/35e.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '2', '颜色', '尺码', '1', '0', null, '1249547077', '1249547077', '1', 'data/files/store_2/goods_179/small_200908060822598478.jpg', '1', '21', '32', '0', '0', '99.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('2', '2', 'material', '花色高邦运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0026.gif\" alt=\"\" /></p>\r\n<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0027.jpg\" alt=\"\" /></p>\r\n<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0028.jpg\" alt=\"\" /></p>', '178', '女鞋	休闲球鞋(不露脚背)', 'G-Star', '2', '颜色', '尺码', '1', '0', null, '1249547390', '1249547390', '4', 'data/files/store_2/goods_131/small_200908060828517782.jpg', '1', '172', '178', '0', '0', '188.00', '', '300.00', '0');
INSERT INTO `ecm_goods` VALUES ('3', '2', 'material', '09新款职业女裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/34a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/34b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/34c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/34d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249547562', '1249547562', '8', 'data/files/store_2/goods_107/small_200908060831473107.jpg', '1', '21', '32', '0', '0', '238.00', '', '500.00', '0');
INSERT INTO `ecm_goods` VALUES ('4', '2', 'material', '09新款韩版淑连衣裙', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/24.jpg\" alt=\"\" /></div>', '36', '女装/女士精品	连衣裙', 'Lee', '0', '', '', '1', '0', null, '1249547772', '1249547772', '9', 'data/files/store_2/goods_66/small_200908060834263919.jpg', '1', '21', '36', '0', '0', '170.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('5', '2', 'material', '2009耐克新款运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0025.jpg\" alt=\"\" /></p>', '191', '运动鞋	全能鞋', 'Nike', '2', '颜色', '尺码', '1', '0', null, '1249547890', '1249547960', '11', 'data/files/store_2/goods_70/small_200908060837502713.jpg', '1', '73', '185', '191', '0', '688.00', '', '799.00', '0');
INSERT INTO `ecm_goods` VALUES ('6', '2', 'material', '包邮韩版经典长袖雪纺下摆针织连衣裙', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/23.jpg\" alt=\"\" /></div>', '36', '女装/女士精品	连衣裙', 'Nike', '0', '', '', '1', '0', null, '1249548137', '1249548137', '15', 'data/files/store_2/goods_95/small_200908060841358079.jpg', '1', '21', '36', '0', '0', '170.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('7', '2', 'material', '09春款专柜正品奢华系列9分裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/36a.jpg\" alt=\"\" /><br /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/36c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/36d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprite', '2', '颜色', '尺码', '1', '0', null, '1249549645', '1249549645', '16', 'data/files/store_2/goods_186/small_200908060906263554.jpg', '1', '21', '32', '0', '0', '178.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('8', '2', 'material', '彪马精品练功鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0024.jpg\" alt=\"\" /></p>\r\n<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0023.jpg\" alt=\"\" /></p>', '193', '运动鞋	休闲鞋', 'PUM', '2', '颜色', '尺码', '1', '0', null, '1249549693', '1249549816', '19', 'data/files/store_2/goods_187/small_200908060909472569.jpg', '1', '73', '185', '193', '0', '368.00', '', '488.00', '0');
INSERT INTO `ecm_goods` VALUES ('9', '2', 'material', '新女性职业长裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/32a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/32b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/32c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/32d.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/32e.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249549936', '1249549936', '25', 'data/files/store_2/goods_98/small_200908060911381037.jpg', '1', '21', '32', '0', '0', '168.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('10', '2', 'material', '家居横纹休闲长裙', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/22.jpg\" alt=\"\" /></div>', '30', '女装/女士精品	半身裙', 'G-Star', '2', '颜色', '尺码', '1', '0', null, '1249550129', '1249550129', '26', 'data/files/store_2/goods_69/small_200908060914291406.jpg', '1', '21', '30', '0', '0', '170.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('11', '2', 'material', '耐克红粉世家运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0022.gif\" alt=\"\" /></p>', '178', '女鞋	休闲球鞋(不露脚背)', 'Nike', '2', '颜色', '尺码', '1', '0', null, '1249550246', '1249550246', '30', 'data/files/store_2/goods_33/small_200908060917132087.jpg', '1', '172', '178', '0', '0', '268.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('12', '2', 'material', '09新款飞腾运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0020.jpg\" alt=\"\" /></p>\r\n<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0021.jpg\" alt=\"\" /></p>', '187', '运动鞋	跑步鞋', 'PUMA', '0', '', '', '1', '0', null, '1249550348', '1249550348', '34', 'data/files/store_2/goods_123/small_200908060918436837.jpg', '1', '73', '185', '187', '0', '268.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('13', '2', 'material', '秀族09新款韩版淑女七分袖针织雪纺连衣裙', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/21.jpg\" alt=\"\" /></div>', '21', '女装/女士精品', 'DIOR', '0', '', '', '1', '0', null, '1249550465', '1249550465', '35', 'data/files/store_2/goods_24/small_200908060920245196.jpg', '1', '21', '0', '0', '0', '179.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('14', '2', 'material', '春款彩色格纹系列牛仔小脚裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/37a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/37b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/37c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/37d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249550608', '1249550637', '37', 'data/files/store_2/goods_128/small_200908060922084636.jpg', '1', '21', '32', '0', '0', '125.00', '', '159.00', '0');
INSERT INTO `ecm_goods` VALUES ('15', '2', 'material', '耐克混色女式篮球鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0019.jpg\" alt=\"\" /></p>', '178', '女鞋	休闲球鞋(不露脚背)', 'Nike', '2', '颜色', '尺码', '1', '0', null, '1249550754', '1249550754', '38', 'data/files/store_2/goods_147/small_200908060925471585.jpg', '1', '172', '178', '0', '0', '578.00', '', '599.00', '0');
INSERT INTO `ecm_goods` VALUES ('16', '2', 'material', '横纹方格运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0018.jpg\" alt=\"\" /></p>', '178', '女鞋	休闲球鞋(不露脚背)', '李宁', '2', '颜色', '尺码', '1', '0', null, '1249550876', '1249550876', '42', 'data/files/store_2/goods_67/small_200908060927474675.jpg', '1', '172', '178', '0', '0', '128.00', '', '200.00', '0');
INSERT INTO `ecm_goods` VALUES ('17', '2', 'material', '韩E族百搭修身紧腰休闲长裤【灰色】', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/30a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/30b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/30c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/30d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249551261', '1249552157', '55', 'data/files/store_2/goods_121/small_200908060932011437.jpg', '1', '21', '32', '0', '0', '90.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('18', '2', 'material', '春针织淑女连衣裙女装', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/20.jpg\" alt=\"\" /></div>', '30', '女装/女士精品	半身裙', 'Jack & Jones', '0', '', '', '1', '0', null, '1249551437', '1249551437', '47', 'data/files/store_2/goods_195/small_200908060936352784.jpg', '1', '21', '30', '0', '0', '170.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('19', '2', 'material', '罗衣OL气质真丝雪纺百褶裙针织背心裙', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/19.jpg\" alt=\"\" /></div>', '30', '女装/女士精品	半身裙', '美特斯邦威', '0', '', '', '1', '0', null, '1249551552', '1249551552', '48', 'data/files/store_2/goods_109/small_200908060938292631.jpg', '1', '21', '30', '0', '0', '170.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('20', '2', 'material', '小脚牛仔铅笔裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/29a.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/29b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/29c.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/29d.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/29e.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '2', '颜色', '尺码', '1', '0', null, '1249551779', '1249551779', '49', 'data/files/store_2/goods_143/small_200908060942233830.jpg', '1', '21', '32', '0', '0', '129.00', '', '159.00', '0');
INSERT INTO `ecm_goods` VALUES ('21', '2', 'material', '09春季新款简约大方高雅修身针织连衣裙983配腰带', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/18.jpg\" alt=\"\" /></div>', '30', '女装/女士精品	半身裙', 'Adidas', '0', '', '', '1', '0', null, '1249552281', '1249552281', '56', 'data/files/store_2/goods_25/small_200908060950258122.jpg', '1', '21', '30', '0', '0', '170.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('22', '2', 'material', '新款多用型穿珠运动长裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/38a.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/38b.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/38c.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/38d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249552384', '1249552384', '57', 'data/files/store_2/goods_147/small_200908060952274906.jpg', '1', '21', '32', '0', '0', '111.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('23', '2', 'material', '韩.春.搭.闲.优雅修身精致荡领针织连衣裙/配皮带', '<p><img src=\"http://pic.shopex.cn/pictures/goodsdetail/17.jpg\" alt=\"\" /></p>', '30', '女装/女士精品	半身裙', '', '0', '', '', '1', '0', null, '1249552499', '1249552499', '58', 'data/files/store_2/goods_64/small_200908060954245662.jpg', '1', '21', '30', '0', '0', '170.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('24', '2', 'material', '阿迪达斯花式运动鞋', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/danson0017.jpg\" alt=\"\" /></p>', '178', '女鞋	休闲球鞋(不露脚背)', 'Adidas', '2', '颜色', '尺码', '1', '0', null, '1249552624', '1249552624', '59', 'data/files/store_2/goods_20/small_200908060957002218.jpg', '1', '172', '178', '0', '0', '169.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('25', '2', 'material', '春款韩版卡其休闲上衣', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/15.jpg\" alt=\"\" /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/16.jpg\" alt=\"\" /></div>', '26', '女装/女士精品	超短外套', 'Chanel', '2', '颜色', '尺码', '1', '0', null, '1249552779', '1249552779', '63', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '1', '21', '26', '0', '0', '128.00', '', '159.00', '0');
INSERT INTO `ecm_goods` VALUES ('26', '2', 'material', '喜皮风格牛仔短裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/39a.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/39b.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/39c.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/39d.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '0', '', '', '1', '0', null, '1249552900', '1249552900', '67', 'data/files/store_2/goods_47/small_200908061000474424.jpg', '1', '21', '32', '0', '0', '89.00', '', '99.00', '0');
INSERT INTO `ecm_goods` VALUES ('27', '2', 'material', '春季尼龙休闲裤', '<p align=\"center\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/26a.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/26b.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/26c.jpg\" alt=\"\" /> <br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/26d.jpg\" alt=\"\" /><br /><img src=\"http://pic.shopex.cn/pictures/goodsdetail/26e.jpg\" alt=\"\" /></p>', '32', '女装/女士精品	裤子', 'ESprit', '2', '颜色', '尺码', '1', '0', null, '1249553044', '1249553044', '68', 'data/files/store_2/goods_5/small_200908061003253339.jpg', '1', '21', '32', '0', '0', '288.00', '', '199.00', '0');
INSERT INTO `ecm_goods` VALUES ('28', '2', 'material', '欧美精贵密码七分袖名媛洋装款水钻圆领绸缎小外套', '<div style=\"text-align: center;\"><img src=\"http://pic.shopex.cn/pictures/goodsdetail/14.jpg\" alt=\"\" /></div>', '26', '女装/女士精品	超短外套', '美特斯邦威', '0', '', '', '1', '0', null, '1249553192', '1249553238', '73', 'data/files/store_2/goods_115/small_200908061005154170.jpg', '1', '21', '26', '0', '0', '188.00', '', '299.00', '0');
INSERT INTO `ecm_goods` VALUES ('29', '2', 'material', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '<p><img src=\"http://pic.shopex.cn/pictures/goodsdetail/13.jpg\" alt=\"\" /></p>', '22', '女装/女士精品	风衣/长大衣', 'PUMA', '0', '', '', '1', '0', null, '1249553354', '1249553354', '74', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '1', '21', '22', '0', '0', '328.00', '', '599.00', '0');
INSERT INTO `ecm_goods` VALUES ('39', '21', 'material', '测试商品1', '', '38', '女装/女士精品	蕾丝衫/雪纺衫', '', '0', '', '', '1', '0', null, '1469388133', '1469388133', '92', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '1', '21', '38', '0', '0', '189.00', '', null, '0');

-- ----------------------------
-- Table structure for `ecm_goods_attr`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_attr`;
CREATE TABLE `ecm_goods_attr` (
  `gattr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `attr_value` varchar(255) NOT NULL DEFAULT '',
  `attr_id` int(10) unsigned DEFAULT NULL,
  `sort_order` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`gattr_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_attr
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_goods_image`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_image`;
CREATE TABLE `ecm_goods_image` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(255) NOT NULL DEFAULT '',
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `file_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_image
-- ----------------------------
INSERT INTO `ecm_goods_image` VALUES ('1', '1', 'data/files/store_2/goods_179/200908060822598478.jpg', 'data/files/store_2/goods_179/small_200908060822598478.jpg', '255', '3');
INSERT INTO `ecm_goods_image` VALUES ('2', '1', 'data/files/store_2/goods_197/200908060823178267.jpg', 'data/files/store_2/goods_197/small_200908060823178267.jpg', '255', '4');
INSERT INTO `ecm_goods_image` VALUES ('3', '1', 'data/files/store_2/goods_9/200908060823294001.jpg', 'data/files/store_2/goods_9/small_200908060823294001.jpg', '255', '5');
INSERT INTO `ecm_goods_image` VALUES ('4', '1', 'data/files/store_2/goods_25/200908060823452419.jpg', 'data/files/store_2/goods_25/small_200908060823452419.jpg', '255', '6');
INSERT INTO `ecm_goods_image` VALUES ('5', '1', 'data/files/store_2/goods_32/200908060823523184.jpg', 'data/files/store_2/goods_32/small_200908060823523184.jpg', '255', '7');
INSERT INTO `ecm_goods_image` VALUES ('6', '1', 'data/files/store_2/goods_43/200908060824034431.jpg', 'data/files/store_2/goods_43/small_200908060824034431.jpg', '255', '8');
INSERT INTO `ecm_goods_image` VALUES ('7', '2', 'data/files/store_2/goods_131/200908060828517782.jpg', 'data/files/store_2/goods_131/small_200908060828517782.jpg', '255', '9');
INSERT INTO `ecm_goods_image` VALUES ('8', '2', 'data/files/store_2/goods_150/200908060829102798.jpg', 'data/files/store_2/goods_150/small_200908060829102798.jpg', '255', '10');
INSERT INTO `ecm_goods_image` VALUES ('9', '2', 'data/files/store_2/goods_170/200908060829308411.jpg', 'data/files/store_2/goods_170/small_200908060829308411.jpg', '255', '11');
INSERT INTO `ecm_goods_image` VALUES ('10', '3', 'data/files/store_2/goods_107/200908060831473107.jpg', 'data/files/store_2/goods_107/small_200908060831473107.jpg', '255', '12');
INSERT INTO `ecm_goods_image` VALUES ('11', '3', 'data/files/store_2/goods_115/200908060831559591.jpg', 'data/files/store_2/goods_115/small_200908060831559591.jpg', '255', '13');
INSERT INTO `ecm_goods_image` VALUES ('12', '3', 'data/files/store_2/goods_140/200908060832202677.jpg', 'data/files/store_2/goods_140/small_200908060832202677.jpg', '255', '14');
INSERT INTO `ecm_goods_image` VALUES ('13', '3', 'data/files/store_2/goods_147/200908060832272714.jpg', 'data/files/store_2/goods_147/small_200908060832272714.jpg', '255', '15');
INSERT INTO `ecm_goods_image` VALUES ('14', '4', 'data/files/store_2/goods_66/200908060834263919.jpg', 'data/files/store_2/goods_66/small_200908060834263919.jpg', '255', '16');
INSERT INTO `ecm_goods_image` VALUES ('15', '4', 'data/files/store_2/goods_87/200908060834479577.jpg', 'data/files/store_2/goods_87/small_200908060834479577.jpg', '255', '17');
INSERT INTO `ecm_goods_image` VALUES ('16', '4', 'data/files/store_2/goods_105/200908060835054315.jpg', 'data/files/store_2/goods_105/small_200908060835054315.jpg', '255', '18');
INSERT INTO `ecm_goods_image` VALUES ('17', '4', 'data/files/store_2/goods_125/200908060835258625.jpg', 'data/files/store_2/goods_125/small_200908060835258625.jpg', '255', '19');
INSERT INTO `ecm_goods_image` VALUES ('18', '4', 'data/files/store_2/goods_141/200908060835411590.jpg', 'data/files/store_2/goods_141/small_200908060835411590.jpg', '255', '20');
INSERT INTO `ecm_goods_image` VALUES ('19', '4', 'data/files/store_2/goods_155/200908060835558086.jpg', 'data/files/store_2/goods_155/small_200908060835558086.jpg', '255', '21');
INSERT INTO `ecm_goods_image` VALUES ('20', '5', 'data/files/store_2/goods_70/200908060837502713.jpg', 'data/files/store_2/goods_70/small_200908060837502713.jpg', '255', '22');
INSERT INTO `ecm_goods_image` VALUES ('21', '6', 'data/files/store_2/goods_95/200908060841358079.jpg', 'data/files/store_2/goods_95/small_200908060841358079.jpg', '255', '23');
INSERT INTO `ecm_goods_image` VALUES ('22', '6', 'data/files/store_2/goods_108/200908060841484621.jpg', 'data/files/store_2/goods_108/small_200908060841484621.jpg', '255', '24');
INSERT INTO `ecm_goods_image` VALUES ('23', '6', 'data/files/store_2/goods_124/200908060842042302.jpg', 'data/files/store_2/goods_124/small_200908060842042302.jpg', '255', '25');
INSERT INTO `ecm_goods_image` VALUES ('24', '7', 'data/files/store_2/goods_186/200908060906263554.jpg', 'data/files/store_2/goods_186/small_200908060906263554.jpg', '255', '26');
INSERT INTO `ecm_goods_image` VALUES ('25', '7', 'data/files/store_2/goods_13/200908060906532764.jpg', 'data/files/store_2/goods_13/small_200908060906532764.jpg', '255', '27');
INSERT INTO `ecm_goods_image` VALUES ('26', '7', 'data/files/store_2/goods_36/200908060907164774.jpg', 'data/files/store_2/goods_36/small_200908060907164774.jpg', '255', '28');
INSERT INTO `ecm_goods_image` VALUES ('27', '8', 'data/files/store_2/goods_187/200908060909472569.jpg', 'data/files/store_2/goods_187/small_200908060909472569.jpg', '255', '29');
INSERT INTO `ecm_goods_image` VALUES ('28', '8', 'data/files/store_2/goods_2/200908060910023266.jpg', 'data/files/store_2/goods_2/small_200908060910023266.jpg', '255', '30');
INSERT INTO `ecm_goods_image` VALUES ('29', '9', 'data/files/store_2/goods_98/200908060911381037.jpg', 'data/files/store_2/goods_98/small_200908060911381037.jpg', '255', '31');
INSERT INTO `ecm_goods_image` VALUES ('30', '9', 'data/files/store_2/goods_128/200908060912082754.jpg', 'data/files/store_2/goods_128/small_200908060912082754.jpg', '255', '32');
INSERT INTO `ecm_goods_image` VALUES ('31', '10', 'data/files/store_2/goods_69/200908060914291406.jpg', 'data/files/store_2/goods_69/small_200908060914291406.jpg', '255', '33');
INSERT INTO `ecm_goods_image` VALUES ('32', '10', 'data/files/store_2/goods_82/200908060914426191.jpg', 'data/files/store_2/goods_82/small_200908060914426191.jpg', '255', '34');
INSERT INTO `ecm_goods_image` VALUES ('33', '10', 'data/files/store_2/goods_94/200908060914542008.jpg', 'data/files/store_2/goods_94/small_200908060914542008.jpg', '255', '35');
INSERT INTO `ecm_goods_image` VALUES ('34', '10', 'data/files/store_2/goods_126/200908060915269026.jpg', 'data/files/store_2/goods_126/small_200908060915269026.jpg', '255', '36');
INSERT INTO `ecm_goods_image` VALUES ('35', '11', 'data/files/store_2/goods_33/200908060917132087.jpg', 'data/files/store_2/goods_33/small_200908060917132087.jpg', '255', '37');
INSERT INTO `ecm_goods_image` VALUES ('36', '12', 'data/files/store_2/goods_123/200908060918436837.jpg', 'data/files/store_2/goods_123/small_200908060918436837.jpg', '255', '38');
INSERT INTO `ecm_goods_image` VALUES ('37', '12', 'data/files/store_2/goods_142/200908060919027810.jpg', 'data/files/store_2/goods_142/small_200908060919027810.jpg', '255', '39');
INSERT INTO `ecm_goods_image` VALUES ('38', '13', 'data/files/store_2/goods_24/200908060920245196.jpg', 'data/files/store_2/goods_24/small_200908060920245196.jpg', '255', '40');
INSERT INTO `ecm_goods_image` VALUES ('39', '13', 'data/files/store_2/goods_43/200908060920437979.jpg', 'data/files/store_2/goods_43/small_200908060920437979.jpg', '255', '41');
INSERT INTO `ecm_goods_image` VALUES ('40', '13', 'data/files/store_2/goods_54/200908060920546675.jpg', 'data/files/store_2/goods_54/small_200908060920546675.jpg', '255', '42');
INSERT INTO `ecm_goods_image` VALUES ('41', '14', 'data/files/store_2/goods_128/200908060922084636.jpg', 'data/files/store_2/goods_128/small_200908060922084636.jpg', '255', '43');
INSERT INTO `ecm_goods_image` VALUES ('42', '14', 'data/files/store_2/goods_141/200908060922218002.jpg', 'data/files/store_2/goods_141/small_200908060922218002.jpg', '255', '44');
INSERT INTO `ecm_goods_image` VALUES ('43', '14', 'data/files/store_2/goods_29/200908060923496883.jpg', 'data/files/store_2/goods_29/small_200908060923496883.jpg', '255', '45');
INSERT INTO `ecm_goods_image` VALUES ('44', '15', 'data/files/store_2/goods_147/200908060925471585.jpg', 'data/files/store_2/goods_147/small_200908060925471585.jpg', '255', '46');
INSERT INTO `ecm_goods_image` VALUES ('45', '16', 'data/files/store_2/goods_67/200908060927474675.jpg', 'data/files/store_2/goods_67/small_200908060927474675.jpg', '255', '47');
INSERT INTO `ecm_goods_image` VALUES ('46', '17', 'data/files/store_2/goods_121/200908060932011437.jpg', 'data/files/store_2/goods_121/small_200908060932011437.jpg', '255', '48');
INSERT INTO `ecm_goods_image` VALUES ('47', '17', 'data/files/store_2/goods_84/200908060934444841.jpg', 'data/files/store_2/goods_84/small_200908060934444841.jpg', '255', '49');
INSERT INTO `ecm_goods_image` VALUES ('48', '18', 'data/files/store_2/goods_195/200908060936352784.jpg', 'data/files/store_2/goods_195/small_200908060936352784.jpg', '255', '50');
INSERT INTO `ecm_goods_image` VALUES ('49', '18', 'data/files/store_2/goods_8/200908060936481674.jpg', 'data/files/store_2/goods_8/small_200908060936481674.jpg', '255', '51');
INSERT INTO `ecm_goods_image` VALUES ('50', '18', 'data/files/store_2/goods_24/200908060937048695.jpg', 'data/files/store_2/goods_24/small_200908060937048695.jpg', '255', '52');
INSERT INTO `ecm_goods_image` VALUES ('51', '19', 'data/files/store_2/goods_109/200908060938292631.jpg', 'data/files/store_2/goods_109/small_200908060938292631.jpg', '255', '53');
INSERT INTO `ecm_goods_image` VALUES ('52', '19', 'data/files/store_2/goods_124/200908060938443027.jpg', 'data/files/store_2/goods_124/small_200908060938443027.jpg', '255', '54');
INSERT INTO `ecm_goods_image` VALUES ('53', '19', 'data/files/store_2/goods_142/200908060939026685.jpg', 'data/files/store_2/goods_142/small_200908060939026685.jpg', '255', '55');
INSERT INTO `ecm_goods_image` VALUES ('54', '20', 'data/files/store_2/goods_143/200908060942233830.jpg', 'data/files/store_2/goods_143/small_200908060942233830.jpg', '255', '56');
INSERT INTO `ecm_goods_image` VALUES ('55', '20', 'data/files/store_2/goods_156/200908060942363092.jpg', 'data/files/store_2/goods_156/small_200908060942363092.jpg', '255', '57');
INSERT INTO `ecm_goods_image` VALUES ('56', '20', 'data/files/store_2/goods_166/200908060942462672.jpg', 'data/files/store_2/goods_166/small_200908060942462672.jpg', '255', '58');
INSERT INTO `ecm_goods_image` VALUES ('57', '21', 'data/files/store_2/goods_25/200908060950258122.jpg', 'data/files/store_2/goods_25/small_200908060950258122.jpg', '255', '59');
INSERT INTO `ecm_goods_image` VALUES ('58', '21', 'data/files/store_2/goods_39/200908060950399637.jpg', 'data/files/store_2/goods_39/small_200908060950399637.jpg', '255', '60');
INSERT INTO `ecm_goods_image` VALUES ('59', '21', 'data/files/store_2/goods_55/200908060950555738.jpg', 'data/files/store_2/goods_55/small_200908060950555738.jpg', '255', '61');
INSERT INTO `ecm_goods_image` VALUES ('60', '21', 'data/files/store_2/goods_67/200908060951072027.jpg', 'data/files/store_2/goods_67/small_200908060951072027.jpg', '255', '62');
INSERT INTO `ecm_goods_image` VALUES ('61', '22', 'data/files/store_2/goods_147/200908060952274906.jpg', 'data/files/store_2/goods_147/small_200908060952274906.jpg', '255', '63');
INSERT INTO `ecm_goods_image` VALUES ('62', '22', 'data/files/store_2/goods_157/200908060952376888.jpg', 'data/files/store_2/goods_157/small_200908060952376888.jpg', '255', '64');
INSERT INTO `ecm_goods_image` VALUES ('63', '23', 'data/files/store_2/goods_64/200908060954245662.jpg', 'data/files/store_2/goods_64/small_200908060954245662.jpg', '255', '65');
INSERT INTO `ecm_goods_image` VALUES ('64', '23', 'data/files/store_2/goods_72/200908060954323544.jpg', 'data/files/store_2/goods_72/small_200908060954323544.jpg', '255', '66');
INSERT INTO `ecm_goods_image` VALUES ('65', '23', 'data/files/store_2/goods_86/200908060954465326.jpg', 'data/files/store_2/goods_86/small_200908060954465326.jpg', '255', '67');
INSERT INTO `ecm_goods_image` VALUES ('66', '24', 'data/files/store_2/goods_20/200908060957002218.jpg', 'data/files/store_2/goods_20/small_200908060957002218.jpg', '255', '68');
INSERT INTO `ecm_goods_image` VALUES ('67', '25', 'data/files/store_2/goods_139/200908060958592106.jpg', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '255', '69');
INSERT INTO `ecm_goods_image` VALUES ('68', '25', 'data/files/store_2/goods_151/200908060959114414.jpg', 'data/files/store_2/goods_151/small_200908060959114414.jpg', '255', '70');
INSERT INTO `ecm_goods_image` VALUES ('69', '25', 'data/files/store_2/goods_166/200908060959265796.jpg', 'data/files/store_2/goods_166/small_200908060959265796.jpg', '255', '71');
INSERT INTO `ecm_goods_image` VALUES ('70', '26', 'data/files/store_2/goods_47/200908061000474424.jpg', 'data/files/store_2/goods_47/small_200908061000474424.jpg', '255', '72');
INSERT INTO `ecm_goods_image` VALUES ('71', '26', 'data/files/store_2/goods_57/200908061000576924.jpg', 'data/files/store_2/goods_57/small_200908061000576924.jpg', '255', '73');
INSERT INTO `ecm_goods_image` VALUES ('72', '26', 'data/files/store_2/goods_71/200908061001114276.jpg', 'data/files/store_2/goods_71/small_200908061001114276.jpg', '255', '74');
INSERT INTO `ecm_goods_image` VALUES ('73', '26', 'data/files/store_2/goods_86/200908061001263175.jpg', 'data/files/store_2/goods_86/small_200908061001263175.jpg', '255', '75');
INSERT INTO `ecm_goods_image` VALUES ('74', '27', 'data/files/store_2/goods_5/200908061003253339.jpg', 'data/files/store_2/goods_5/small_200908061003253339.jpg', '255', '76');
INSERT INTO `ecm_goods_image` VALUES ('75', '27', 'data/files/store_2/goods_18/200908061003382600.jpg', 'data/files/store_2/goods_18/small_200908061003382600.jpg', '255', '77');
INSERT INTO `ecm_goods_image` VALUES ('76', '27', 'data/files/store_2/goods_29/200908061003494534.jpg', 'data/files/store_2/goods_29/small_200908061003494534.jpg', '255', '78');
INSERT INTO `ecm_goods_image` VALUES ('77', '28', 'data/files/store_2/goods_115/200908061005154170.jpg', 'data/files/store_2/goods_115/small_200908061005154170.jpg', '255', '79');
INSERT INTO `ecm_goods_image` VALUES ('78', '28', 'data/files/store_2/goods_14/200908061006541461.jpg', 'data/files/store_2/goods_14/small_200908061006541461.jpg', '255', '80');
INSERT INTO `ecm_goods_image` VALUES ('79', '28', 'data/files/store_2/goods_26/200908061007068653.jpg', 'data/files/store_2/goods_26/small_200908061007068653.jpg', '255', '81');
INSERT INTO `ecm_goods_image` VALUES ('80', '29', 'data/files/store_2/goods_121/200908061008412008.jpg', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '255', '82');
INSERT INTO `ecm_goods_image` VALUES ('81', '29', 'data/files/store_2/goods_127/200908061008473587.jpg', 'data/files/store_2/goods_127/small_200908061008473587.jpg', '255', '83');
INSERT INTO `ecm_goods_image` VALUES ('106', '0', 'data/files/store_21/goods_100/201607281728201032.jpg', 'data/files/store_21/goods_100/small_201607281728201032.jpg', '255', '108');
INSERT INTO `ecm_goods_image` VALUES ('105', '39', 'data/files/store_21/goods_94/201607251121348958.jpg', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '1', '107');

-- ----------------------------
-- Table structure for `ecm_goods_integral`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_integral`;
CREATE TABLE `ecm_goods_integral` (
  `goods_id` int(11) NOT NULL,
  `max_exchange` int(11) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_integral
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_goods_prop`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_prop`;
CREATE TABLE `ecm_goods_prop` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_prop
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_goods_prop_value`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_prop_value`;
CREATE TABLE `ecm_goods_prop_value` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `prop_value` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_prop_value
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_goods_pvs`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_pvs`;
CREATE TABLE `ecm_goods_pvs` (
  `goods_id` int(11) NOT NULL,
  `pvs` text NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_pvs
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_goods_qa`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_qa`;
CREATE TABLE `ecm_goods_qa` (
  `ques_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_content` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `email` varchar(60) NOT NULL,
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `reply_content` varchar(255) NOT NULL,
  `time_post` int(10) unsigned NOT NULL,
  `time_reply` int(10) unsigned NOT NULL,
  `if_new` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL DEFAULT 'goods',
  PRIMARY KEY (`ques_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `goods_id` (`item_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_qa
-- ----------------------------
INSERT INTO `ecm_goods_qa` VALUES ('1', '这个商品有没有型号的分别', '22', '21', 'tiantian@sina.cn', '39', '测试商品1', '', '1469669856', '0', '1', 'goods');

-- ----------------------------
-- Table structure for `ecm_goods_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_spec`;
CREATE TABLE `ecm_goods_spec` (
  `spec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `spec_1` varchar(60) NOT NULL DEFAULT '',
  `spec_2` varchar(60) NOT NULL DEFAULT '',
  `color_rgb` varchar(7) NOT NULL DEFAULT '',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`spec_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `price` (`price`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_spec
-- ----------------------------
INSERT INTO `ecm_goods_spec` VALUES ('1', '1', '粉红色', 'XL', '', '99.00', '94', 'G49B7B00DB597F-1');
INSERT INTO `ecm_goods_spec` VALUES ('2', '1', '白色', 'XL', '', '99.00', '108', 'G49B7B00DB597F-2');
INSERT INTO `ecm_goods_spec` VALUES ('3', '1', '黄色', 'XL', '', '99.00', '108', 'G49B7B00DB597F-3');
INSERT INTO `ecm_goods_spec` VALUES ('4', '2', '混色', '38', '', '188.00', '96', '');
INSERT INTO `ecm_goods_spec` VALUES ('5', '2', '混色', '39', '', '198.00', '100', '');
INSERT INTO `ecm_goods_spec` VALUES ('6', '2', '深蓝色', '38', '', '188.00', '80', '');
INSERT INTO `ecm_goods_spec` VALUES ('7', '2', '深蓝色', '39', '', '188.00', '60', '');
INSERT INTO `ecm_goods_spec` VALUES ('8', '3', '', '', '', '238.00', '16', '');
INSERT INTO `ecm_goods_spec` VALUES ('9', '4', '', '', '', '170.00', '22', '');
INSERT INTO `ecm_goods_spec` VALUES ('11', '5', '蓝白混色', '39', '', '688.00', '31', '');
INSERT INTO `ecm_goods_spec` VALUES ('12', '5', '蓝白混色', '40', '', '688.00', '39', '');
INSERT INTO `ecm_goods_spec` VALUES ('13', '5', '蓝白混色', '41', '', '688.00', '99', '');
INSERT INTO `ecm_goods_spec` VALUES ('14', '5', '蓝白混色', '42', '', '688.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('15', '6', '', '', '', '170.00', '89', '');
INSERT INTO `ecm_goods_spec` VALUES ('16', '7', '黑色', '均码', '', '178.00', '20', '');
INSERT INTO `ecm_goods_spec` VALUES ('17', '7', '银色', '均码', '', '178.00', '30', '');
INSERT INTO `ecm_goods_spec` VALUES ('19', '8', '金色', '36', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('20', '8', '金色', '37', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('21', '8', '金色', '38', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('22', '8', '黑色', '36', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('23', '8', '黑色', '37', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('24', '8', '黑色', '38', '', '368.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('25', '9', '', '', '', '168.00', '28', '');
INSERT INTO `ecm_goods_spec` VALUES ('26', '10', '蓝色', 'S', '', '170.00', '9', '');
INSERT INTO `ecm_goods_spec` VALUES ('27', '10', '蓝色', 'M', '', '170.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('28', '10', '蓝色', 'X', '', '170.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('29', '10', '蓝色', 'XL', '', '170.00', '10', '');
INSERT INTO `ecm_goods_spec` VALUES ('30', '11', '粉红', '36', '', '268.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('31', '11', '粉红', '37', '', '268.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('32', '11', '粉红', '38', '', '268.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('33', '11', '粉红', '39', '', '268.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('34', '12', '', '', '', '268.00', '29', '');
INSERT INTO `ecm_goods_spec` VALUES ('35', '13', '', '', '', '179.00', '500', '');
INSERT INTO `ecm_goods_spec` VALUES ('37', '14', '', '', '', '125.00', '33', '');
INSERT INTO `ecm_goods_spec` VALUES ('38', '15', '混色', '36', '', '578.00', '92', '');
INSERT INTO `ecm_goods_spec` VALUES ('39', '15', '混色', '37', '', '578.00', '92', '');
INSERT INTO `ecm_goods_spec` VALUES ('40', '15', '混色', '38', '', '578.00', '92', '');
INSERT INTO `ecm_goods_spec` VALUES ('41', '15', '混色', '39', '', '578.00', '92', '');
INSERT INTO `ecm_goods_spec` VALUES ('42', '16', '方格混色', '37', '', '128.00', '798', '');
INSERT INTO `ecm_goods_spec` VALUES ('43', '16', '方格混色', '38', '', '130.00', '700', '');
INSERT INTO `ecm_goods_spec` VALUES ('44', '16', '方格白色', '39', '', '126.00', '738', '');
INSERT INTO `ecm_goods_spec` VALUES ('55', '17', '', '', '', '90.00', '87', '');
INSERT INTO `ecm_goods_spec` VALUES ('47', '18', '', '', '', '170.00', '30', '');
INSERT INTO `ecm_goods_spec` VALUES ('48', '19', '', '', '', '170.00', '89', '');
INSERT INTO `ecm_goods_spec` VALUES ('49', '20', '深蓝色', 'M', '', '129.00', '103', '');
INSERT INTO `ecm_goods_spec` VALUES ('50', '20', '深蓝色', 'X', '', '129.00', '99', '');
INSERT INTO `ecm_goods_spec` VALUES ('51', '20', '白色', 'M', '', '129.00', '99', '');
INSERT INTO `ecm_goods_spec` VALUES ('52', '20', '白色', 'X', '', '129.00', '98', '');
INSERT INTO `ecm_goods_spec` VALUES ('53', '20', '粉红色', 'M', '', '129.00', '99', '');
INSERT INTO `ecm_goods_spec` VALUES ('54', '20', '粉红色', 'X', '', '129.00', '99', '');
INSERT INTO `ecm_goods_spec` VALUES ('56', '21', '', '', '', '170.00', '85', '');
INSERT INTO `ecm_goods_spec` VALUES ('57', '22', '', '', '', '111.00', '36', '');
INSERT INTO `ecm_goods_spec` VALUES ('58', '23', '', '', '', '170.00', '500', '');
INSERT INTO `ecm_goods_spec` VALUES ('59', '24', '花色', '36', '', '169.00', '885', '');
INSERT INTO `ecm_goods_spec` VALUES ('60', '24', '花色', '37', '', '169.00', '887', '');
INSERT INTO `ecm_goods_spec` VALUES ('61', '24', '花色', '38', '', '169.00', '888', '');
INSERT INTO `ecm_goods_spec` VALUES ('62', '24', '花色', '39', '', '169.00', '888', '');
INSERT INTO `ecm_goods_spec` VALUES ('63', '25', '灰色', 'S', '', '128.00', '84', '');
INSERT INTO `ecm_goods_spec` VALUES ('64', '25', '灰色', 'M', '', '128.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('65', '25', '灰色', 'L', '', '128.00', '88', '');
INSERT INTO `ecm_goods_spec` VALUES ('66', '25', '灰色', 'XL', '', '128.00', '87', '');
INSERT INTO `ecm_goods_spec` VALUES ('67', '26', '', '', '', '89.00', '97', '');
INSERT INTO `ecm_goods_spec` VALUES ('68', '27', '卡其色', 'M', '', '288.00', '286', '');
INSERT INTO `ecm_goods_spec` VALUES ('69', '27', '卡其色', 'X', '', '288.00', '282', '');
INSERT INTO `ecm_goods_spec` VALUES ('70', '27', '深蓝', 'M', '', '288.00', '286', '');
INSERT INTO `ecm_goods_spec` VALUES ('71', '27', '深蓝', 'X', '', '288.00', '282', '');
INSERT INTO `ecm_goods_spec` VALUES ('73', '28', '', '', '', '188.00', '2221', '');
INSERT INTO `ecm_goods_spec` VALUES ('74', '29', '', '', '', '328.00', '85', '');
INSERT INTO `ecm_goods_spec` VALUES ('92', '39', '', '', '', '189.00', '78', '');

-- ----------------------------
-- Table structure for `ecm_goods_statistics`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_goods_statistics`;
CREATE TABLE `ecm_goods_statistics` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `collects` int(10) unsigned NOT NULL DEFAULT '0',
  `carts` int(10) unsigned NOT NULL DEFAULT '0',
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_goods_statistics
-- ----------------------------
INSERT INTO `ecm_goods_statistics` VALUES ('1', '26', '1', '5', '5', '1', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('2', '8', '0', '3', '4', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('3', '28', '2', '7', '4', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('4', '17', '1', '5', '8', '17', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('5', '7', '0', '1', '2', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('6', '5', '1', '3', '2', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('7', '1', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('8', '2', '0', '1', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('9', '2', '0', '1', '1', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('10', '1', '1', '2', '1', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('11', '0', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('12', '0', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('13', '1', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('14', '2', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('15', '0', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('16', '69', '1', '8', '2', '56', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('17', '9', '0', '2', '1', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('18', '6', '0', '3', '3', '0', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('19', '7', '0', '7', '4', '1', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('20', '5', '0', '2', '2', '1', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('21', '6', '0', '3', '3', '0', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('22', '5', '0', '2', '2', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('23', '8', '0', '0', '0', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('24', '20', '0', '4', '4', '2', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('25', '6', '1', '11', '3', '16', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('26', '10', '1', '5', '2', '0', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('27', '4', '0', '3', '1', '0', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('28', '4', '0', '2', '1', '1', '1');
INSERT INTO `ecm_goods_statistics` VALUES ('29', '51', '0', '13', '14', '6', '0');
INSERT INTO `ecm_goods_statistics` VALUES ('39', '39', '1', '20', '17', '15', '0');

-- ----------------------------
-- Table structure for `ecm_groupbuy`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_groupbuy`;
CREATE TABLE `ecm_groupbuy` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL DEFAULT '',
  `group_image` varchar(255) NOT NULL,
  `group_desc` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `spec_price` text NOT NULL,
  `min_quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_per_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_groupbuy
-- ----------------------------
INSERT INTO `ecm_groupbuy` VALUES ('3', 'z-test', '', '哈哈哈团购测试', '1469468718', '1470470399', '4', '2', 'a:1:{i:9;a:1:{s:5:\"price\";s:6:\"150.00\";}}', '10', '0', '4', '0', '18');
INSERT INTO `ecm_groupbuy` VALUES ('5', '测试团购', '', '测试团购，测试团购，测试团购，测试团购，测试团购，测试团购，测试团购，测试团购，测试团购，', '1469669590', '1470297599', '39', '21', 'a:1:{i:92;a:1:{s:5:\"price\";s:6:\"118.00\";}}', '100', '1', '4', '0', '0');
INSERT INTO `ecm_groupbuy` VALUES ('4', 'z-t1', '', 't1', '1469477067', '1470815999', '26', '2', 'a:1:{i:67;a:1:{s:5:\"price\";s:5:\"80.00\";}}', '10', '0', '4', '0', '3');

-- ----------------------------
-- Table structure for `ecm_groupbuy_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_groupbuy_log`;
CREATE TABLE `ecm_groupbuy_log` (
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `spec_quantity` text NOT NULL,
  `linkman` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_groupbuy_log
-- ----------------------------
INSERT INTO `ecm_groupbuy_log` VALUES ('3', '3', 'buyer', '1', 'a:1:{i:9;a:2:{s:4:\"spec\";s:12:\"默认规格\";s:3:\"qty\";s:1:\"1\";}}', 'zzz', '15900000000', '0', '1469483854');

-- ----------------------------
-- Table structure for `ecm_integral`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_integral`;
CREATE TABLE `ecm_integral` (
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_integral
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_integral_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_integral_log`;
CREATE TABLE `ecm_integral_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `order_sn` varchar(20) NOT NULL,
  `changes` decimal(25,2) NOT NULL,
  `balance` decimal(25,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_integral_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_mail_queue`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_mail_queue`;
CREATE TABLE `ecm_mail_queue` (
  `queue_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mail_to` varchar(150) NOT NULL DEFAULT '',
  `mail_encoding` varchar(50) NOT NULL DEFAULT '',
  `mail_subject` varchar(255) NOT NULL DEFAULT '',
  `mail_body` text NOT NULL,
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `err_num` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `lock_expiry` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`queue_id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_mail_queue
-- ----------------------------
INSERT INTO `ecm_mail_queue` VALUES ('194', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623071838。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=32\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=32</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:48</p>', '1', '1', '1471459686', '1471459771');
INSERT INTO `ecm_mail_queue` VALUES ('193', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623099421，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623099421。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=31\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=31</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:43</p>', '1', '3', '1471459401', '1471459771');
INSERT INTO `ecm_mail_queue` VALUES ('198', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623073260。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=33\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=33</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:49</p>', '1', '3', '1471459757', '1471462245');
INSERT INTO `ecm_mail_queue` VALUES ('192', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623099421已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623099421发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：123456</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=31\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=31</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:43</p>', '1', '2', '1471459387', '1471459695');
INSERT INTO `ecm_mail_queue` VALUES ('191', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623099421，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=31\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=31</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:42</p>', '1', '2', '1471459360', '1471459695');
INSERT INTO `ecm_mail_queue` VALUES ('190', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623099421。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=31\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=31</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:42</p>', '1', '2', '1471459360', '1471459695');
INSERT INTO `ecm_mail_queue` VALUES ('195', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623071838，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=32\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=32</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:48</p>', '1', '1', '1471459686', '1471459771');
INSERT INTO `ecm_mail_queue` VALUES ('196', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623071838已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623071838发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：123456</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=32\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=32</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:48</p>', '1', '1', '1471459713', '1471459771');
INSERT INTO `ecm_mail_queue` VALUES ('197', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623071838，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623071838。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=32\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=32</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:48</p>', '1', '2', '1471459728', '1471460008');
INSERT INTO `ecm_mail_queue` VALUES ('208', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623010820，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=35\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=35</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:39</p>', '1', '1', '1471462745', '1471462864');
INSERT INTO `ecm_mail_queue` VALUES ('206', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623055018，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623055018。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=34\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=34</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:38</p>', '1', '2', '1471462693', '1471462864');
INSERT INTO `ecm_mail_queue` VALUES ('207', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623010820。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=35\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=35</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:39</p>', '1', '1', '1471462745', '1471462864');
INSERT INTO `ecm_mail_queue` VALUES ('204', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623055018，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=34\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=34</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:36</p>', '1', '2', '1471462576', '1471462876');
INSERT INTO `ecm_mail_queue` VALUES ('205', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623055018已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623055018发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：111111</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=34\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=34</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:37</p>', '1', '2', '1471462678', '1471462876');
INSERT INTO `ecm_mail_queue` VALUES ('199', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623073260，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=33\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=33</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:49</p>', '1', '3', '1471459757', '1471462245');
INSERT INTO `ecm_mail_queue` VALUES ('203', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623055018。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=34\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=34</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:36</p>', '1', '2', '1471462576', '1471462876');
INSERT INTO `ecm_mail_queue` VALUES ('215', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623023319。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=37\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=37</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:47</p>', '1', '1', '1471463237', '1471463414');
INSERT INTO `ecm_mail_queue` VALUES ('216', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623023319，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=37\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=37</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:47</p>', '1', '1', '1471463237', '1471463414');
INSERT INTO `ecm_mail_queue` VALUES ('217', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623023319已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623023319发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：7675767</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=37\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=37</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:47</p>', '1', '1', '1471463267', '1471463414');
INSERT INTO `ecm_mail_queue` VALUES ('200', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623073260已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623073260发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：122122</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=33\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=33</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 10:49</p>', '1', '3', '1471459799', '1471462245');
INSERT INTO `ecm_mail_queue` VALUES ('209', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623010820已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623010820发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：12121211</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=35\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=35</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:39</p>', '1', '2', '1471462779', '1471463420');
INSERT INTO `ecm_mail_queue` VALUES ('210', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623010820，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623010820。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=35\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=35</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:39</p>', '1', '4', '1471462797', '1471463420');
INSERT INTO `ecm_mail_queue` VALUES ('211', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单已生成', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">您在演示站上下的订单已生成，订单号1623085834。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=36\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=36</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:41</p>', '1', '3', '1471462861', '1471463420');
INSERT INTO `ecm_mail_queue` VALUES ('212', 'summer@sina.com', 'utf-8', '演示站提醒:您有一个新订单需要处理', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">您有一个新的订单需要处理，订单号1623085834，请尽快处理。</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=36\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=36</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:41</p>', '1', '3', '1471462861', '1471463420');
INSERT INTO `ecm_mail_queue` VALUES ('213', 'lucky@sina.cn', 'utf-8', '演示站提醒:您的订单1623085834已发货', '<p>尊敬的lucky:</p>\n<p style=\"padding-left: 30px;\">与您交易的店铺冰之渴望已经给您的订单1623085834发货了，请注意查收。</p>\n<p style=\"padding-left: 30px;\">发货单号：12121</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=36\">http://ecmos.t.360cd.cn/index.php?app=buyer_order&amp;act=view&amp;order_id=36</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:41</p>', '1', '3', '1471462892', '1471463420');
INSERT INTO `ecm_mail_queue` VALUES ('214', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623085834，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623085834。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=36\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=36</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:42</p>', '1', '3', '1471462931', '1471463414');
INSERT INTO `ecm_mail_queue` VALUES ('218', 'summer@sina.com', 'utf-8', '演示站提醒:买家确认了与您交易的订单1623023319，交易完成', '<p>尊敬的冰之渴望:</p>\n<p style=\"padding-left: 30px;\">买家lucky已经确认了与您交易的订单1623023319。交易完成</p>\n<p style=\"padding-left: 30px;\">查看订单详细信息请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=37\">http://ecmos.t.360cd.cn/index.php?app=seller_order&amp;act=view&amp;order_id=37</a></p>\n<p style=\"padding-left: 30px;\">查看您的订单列表管理页请点击以下链接</p>\n<p style=\"padding-left: 30px;\"><a href=\"http://ecmos.t.360cd.cn/index.php?app=seller_order\">http://ecmos.t.360cd.cn/index.php?app=seller_order</a></p>\n<p style=\"text-align: right;\">演示站</p>\n<p style=\"text-align: right;\">2016-08-18 11:47</p>', '1', '1', '1471463278', '1471463414');

-- ----------------------------
-- Table structure for `ecm_member`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_member`;
CREATE TABLE `ecm_member` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `real_name` varchar(60) DEFAULT NULL,
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` date DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `im_qq` varchar(60) DEFAULT NULL,
  `im_msn` varchar(60) DEFAULT NULL,
  `im_skype` varchar(60) DEFAULT NULL,
  `im_yahoo` varchar(60) DEFAULT NULL,
  `im_aliww` varchar(60) DEFAULT NULL,
  `reg_time` int(10) unsigned DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `last_ip` varchar(15) DEFAULT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `ugrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `portrait` varchar(255) DEFAULT NULL,
  `outer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `activation` varchar(60) DEFAULT NULL,
  `feed_config` text NOT NULL,
  `valid_code` varchar(40) NOT NULL,
  `valid_status` tinyint(4) NOT NULL DEFAULT '0',
  `expire_time` int(11) NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL COMMENT '直接上级用户',
  `parent_path` varchar(2000) NOT NULL DEFAULT ',0' COMMENT '所有上级用户',
  `locked` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`) USING BTREE,
  KEY `email` (`email`) USING BTREE,
  KEY `outer_id` (`outer_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_member
-- ----------------------------
INSERT INTO `ecm_member` VALUES ('1', 'admin', 'seema@zoliu.cn', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '0', null, null, null, '', '', null, null, null, '1421048268', '1471540884', '127.0.0.1', '91', '0', '', '0', null, '', '', '0', '0', '1', ',0,1', '0');
INSERT INTO `ecm_member` VALUES ('2', 'seller', 'seller@ecmall.com', 'e10adc3949ba59abbe56e057f20f883e', '超级卖家', '0', null, null, null, null, null, null, null, null, '1421048309', '1471546177', '127.0.0.1', '71', '0', null, '0', null, '', '', '0', '0', '1', ',0,1', '0');
INSERT INTO `ecm_member` VALUES ('3', 'buyer', 'buyer@ecmall.com', 'e10adc3949ba59abbe56e057f20f883e', '超级买家', '0', null, null, null, null, null, null, null, null, '1421048309', '1471025587', '127.0.0.1', '68', '0', null, '0', null, '', '', '0', '0', '2', ',0,1,2', '0');
INSERT INTO `ecm_member` VALUES ('22', 'tiantian', 'tiantian@sina.cn', 'e10adc3949ba59abbe56e057f20f883e', null, '0', null, null, null, null, null, null, null, null, '1469138175', '1471458126', '192.168.1.5', '9', '0', null, '0', null, '', '', '0', '0', '21', ',0,1,2,21', '0');
INSERT INTO `ecm_member` VALUES ('23', 'lucky', 'lucky@sina.cn', 'e10adc3949ba59abbe56e057f20f883e', null, '0', null, null, null, null, null, null, null, null, '1469139490', '1471462433', '192.168.1.5', '4', '0', null, '0', null, '', '', '0', '0', '22', ',0,1,2,21,22', '0');
INSERT INTO `ecm_member` VALUES ('21', 'summer', 'summer@sina.com', 'e10adc3949ba59abbe56e057f20f883e', '夏天', '0', '1922-02-02', null, null, '', '', null, null, null, '1469136102', '1471458103', '192.168.1.5', '11', '0', 'data/files/mall/portrait/1/21.jpg', '0', null, '', '', '0', '0', '2', ',0,1,2', '0');
INSERT INTO `ecm_member` VALUES ('24', 'test', 'test@ecm.com', 'e10adc3949ba59abbe56e057f20f883e', '', '0', null, null, null, '', '', null, null, null, '1469657821', '1469664631', '127.0.0.1', '2', '0', null, '0', null, '', '', '0', '0', '1', ',0,1', '0');

-- ----------------------------
-- Table structure for `ecm_member_ext`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_member_ext`;
CREATE TABLE `ecm_member_ext` (
  `user_id` int(11) unsigned NOT NULL,
  `grade_id` int(11) unsigned DEFAULT NULL,
  `integral` int(10) unsigned DEFAULT NULL,
  `total_integral` int(10) unsigned DEFAULT NULL,
  `total_buy` decimal(20,4) unsigned DEFAULT NULL,
  `update_time` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_member_ext
-- ----------------------------
INSERT INTO `ecm_member_ext` VALUES ('24', '1', '0', '0', '0.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('21', '1', '0', '0', '0.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('23', '3', '1282', '4988', '1705.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('22', '3', '0', '0', '0.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('3', '1', '0', '0', '0.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('2', '2', '700', '1100', '0.0000', '1471387536');
INSERT INTO `ecm_member_ext` VALUES ('1', '3', '0', '0', '0.0000', '1471387536');

-- ----------------------------
-- Table structure for `ecm_member_grade`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_member_grade`;
CREATE TABLE `ecm_member_grade` (
  `grade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` char(32) DEFAULT NULL,
  `priority` int(10) unsigned DEFAULT NULL,
  `upgrade_buy` decimal(20,4) unsigned DEFAULT NULL,
  `upgrade_integral` int(10) unsigned DEFAULT NULL,
  `buy_tc` decimal(10,4) unsigned DEFAULT NULL,
  `sell_tc` decimal(10,4) unsigned DEFAULT NULL,
  `discount` decimal(10,4) unsigned DEFAULT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_member_grade
-- ----------------------------
INSERT INTO `ecm_member_grade` VALUES ('6', '五星会员', '6', '100000.0000', '50000', '0.1000', '0.1200', '0.7000');
INSERT INTO `ecm_member_grade` VALUES ('5', '四星会员', '5', '50000.0000', '25000', '0.0800', '0.1000', '0.8000');
INSERT INTO `ecm_member_grade` VALUES ('4', '三星会员', '4', '10000.0000', '5000', '0.0600', '0.0800', '0.8500');
INSERT INTO `ecm_member_grade` VALUES ('3', '二星会员', '3', '5000.0000', '2500', '0.0400', '0.0600', '0.9000');
INSERT INTO `ecm_member_grade` VALUES ('2', '一星会员', '2', '1000.0000', '500', '0.0200', '0.0400', '0.9500');
INSERT INTO `ecm_member_grade` VALUES ('1', '普通会员', '1', '0.0000', '0', '0.0100', '0.0200', '0.0000');

-- ----------------------------
-- Table structure for `ecm_message`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_message`;
CREATE TABLE `ecm_message` (
  `msg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` int(10) unsigned NOT NULL DEFAULT '0',
  `to_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `from_id` (`from_id`) USING BTREE,
  KEY `to_id` (`to_id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_message
-- ----------------------------
INSERT INTO `ecm_message` VALUES ('6', '0', '3', '退货/退款处理结果提醒', '您的订单1620676440申请退货/退款,管理员已经处理，请到我的退货/退款查看', '1469398289', '1469398289', '0', '0', '3');
INSERT INTO `ecm_message` VALUES ('5', '0', '2', '退货/退款提醒', 'buyer正在申请退货,退货原因：maijia', '1469397163', '1469397163', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('4', '0', '21', '', '恭喜，您的店铺已开通，赶快来用户中心发布商品吧。', '1469387384', '1469387384', '0', '0', '3');
INSERT INTO `ecm_message` VALUES ('7', '0', '22', '退货/退款处理结果提醒', '您的订单1620621396申请退货/退款,管理员已经处理，请到我的退货/退款查看', '1469398877', '1469398877', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('8', '0', '24', '', '您的店铺已被删除', '1469664595', '1469664595', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('9', '21', '22', '', 'tiantiantiantiantiantiantiantiantiantiantiantiantiantian', '1469666049', '1469666049', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('10', '0', '22', '', '您收到了 “冰之渴望” 发送来的优惠券 \r\n 优惠金额：10.00 \r\n有效期：2016-07-29 至2016-08-01 \r\n优惠券号码：000000053221 \r\n使用条件：购物满 100.00 即可使用 \r\n店铺地址：[url=http://ecm.t.360cd.cn/index.php?app=store&amp;id=21]冰之渴望[/url]', '1469751208', '1469751208', '0', '0', '3');
INSERT INTO `ecm_message` VALUES ('11', '0', '21', '', '请尽快到“已结束的团购”完成该团购活动，以便买家可以完成交易，如结束后5天未确认完成，该活动将被自动取消,查看[url=http://ecm.kf.360cd.cn:8000/index.php?app=seller_groupbuy&state=end]已结束的团购[/url]', '1470338381', '1470338381', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('12', '0', '2', '', '请尽快到“已结束的团购”完成该团购活动，以便买家可以完成交易，如结束后5天未确认完成，该活动将被自动取消,查看[url=http://ecmos.t.360cd.cn/index.php?app=seller_groupbuy&state=end]已结束的团购[/url]', '1470589509', '1470589509', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('13', '0', '2', '退货/退款提醒', 'buyer正在申请退货,退货原因：ssss', '1470693263', '1470693263', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('14', '0', '2', '', '请尽快到“已结束的团购”完成该团购活动，以便买家可以完成交易，如结束后5天未确认完成，该活动将被自动取消,查看[url=http://ecmos.t.360cd.cn/index.php?app=seller_groupbuy&state=end]已结束的团购[/url]', '1470848710', '1470848710', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('15', '0', '1', '', '团购活动结束5天后卖家未确认完成，活动自动取消，[url=http://ecmos.t.360cd.cn/index.php?app=groupbuy&amp;id=5]查看详情[/url]', '1470848710', '1470848710', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('16', '0', '2', '退货/退款提醒', 'buyer正在申请退货,退货原因：ssssedddddddddddddddddd', '1470878912', '1470878912', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('17', '0', '2', '退货/退款提醒', 'buyer正在申请退货,退货原因：ssssedddddddddddddddddd', '1470878963', '1470878963', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('18', '0', '1', '', '团购活动结束5天后卖家未确认完成，活动自动取消，[url=http://ecmos.t.360cd.cn/index.php?app=groupbuy&amp;id=3]查看详情[/url]', '1470935188', '1470935188', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('19', '0', '3', '', '团购活动结束5天后卖家未确认完成，活动自动取消，[url=http://ecmos.t.360cd.cn/index.php?app=groupbuy&amp;id=3]查看详情[/url]', '1470935188', '1470935188', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('20', '0', '1', '', '团购活动结束5天后卖家未确认完成，活动自动取消，[url=http://ecmos.t.360cd.cn/index.php?app=groupbuy&amp;id=4]查看详情[/url]', '1471285747', '1471285747', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('21', '0', '1', '管理员增加积分-- 得到1000积分 ', '管理员增加积分-- 得到1000积分', '1471291196', '1471291196', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('22', '0', '2', '管理员增加积分-- 得到10000积分 ', '管理员增加积分-- 得到10000积分', '1471299409', '1471299409', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('23', '0', '2', '管理员减少积分-- 消费1000积分 ', '管理员减少积分-- 消费1000积分', '1471303460', '1471303460', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('24', '0', '2', '管理员增加积分-- 得到1000积分 ', '管理员增加积分-- 得到1000积分', '1471311177', '1471311177', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('25', '0', '2', '管理员增加积分-- 得到100积分 ', '管理员增加积分-- 得到100积分', '1471311427', '1471311427', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('26', '0', '2', '管理员增加积分-- 得到100积分 ', '管理员增加积分-- 得到100积分', '1471311577', '1471311577', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('27', '0', '1', '管理员增加积分-- 得到100积分 ', '管理员增加积分-- 得到100积分', '1471385059', '1471385059', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('28', '0', '2', '管理员增加积分-- 得到85积分 ', '管理员增加积分-- 得到85积分', '1471385105', '1471385105', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('29', '0', '1', '管理员减少积分-- 消费1000积分 ', '管理员减少积分-- 消费1000积分', '1471385127', '1471385127', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('30', '0', '1', '管理员增加积分-- 得到1000积分 ', '管理员增加积分-- 得到1000积分', '1471385150', '1471385150', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('31', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471459401', '1471459401', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('32', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471459728', '1471459728', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('33', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471459823', '1471459823', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('34', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471460005', '1471460005', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('35', '0', '23', '登陆赠送积分-- 得到50积分 ', '登陆赠送积分-- 得到50积分', '1471462433', '1471462433', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('36', '0', '23', '购物赠送积分-- 得到567积分 ', '购物赠送积分-- 得到567积分', '1471462693', '1471462693', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('37', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471462797', '1471462797', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('38', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471462931', '1471462931', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('39', '0', '23', '购物赠送积分-- 得到95积分 ', '购物赠送积分-- 得到95积分', '1471463278', '1471463278', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('40', '0', '2', '登陆赠送积分-- 得到50积分 ', '登陆赠送积分-- 得到50积分', '1471480378', '1471480378', '0', '0', '3');
INSERT INTO `ecm_message` VALUES ('41', '0', '2', '登陆赠送积分-- 得到50积分 ', '登陆赠送积分-- 得到50积分', '1471546177', '1471546177', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('42', '0', '2', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', '1471546271', '1471546271', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('43', '0', '2', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', '1471546397', '1471546397', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('44', '0', '2', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', '1471546458', '1471546458', '1', '0', '3');
INSERT INTO `ecm_message` VALUES ('45', '0', '2', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', '1471550447', '1471550447', '1', '0', '3');

-- ----------------------------
-- Table structure for `ecm_module`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_module`;
CREATE TABLE `ecm_module` (
  `module_id` varchar(30) NOT NULL DEFAULT '',
  `module_name` varchar(100) NOT NULL DEFAULT '',
  `module_version` varchar(5) NOT NULL DEFAULT '',
  `module_desc` text NOT NULL,
  `module_config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_module
-- ----------------------------
INSERT INTO `ecm_module` VALUES ('rapp', '应用中心', '1.0', '安装以后，可以直接安装需要的第三方应用', '', '1');
INSERT INTO `ecm_module` VALUES ('msg', '手机短信', '1.0', '安装以后，用户可以使用手机短信收发功能', '', '1');
INSERT INTO `ecm_module` VALUES ('member_ext', '卓流应用-会员分级', '1.0', '会员信息扩展', '', '1');
INSERT INTO `ecm_module` VALUES ('point', '卓流应用-会员积分', '1.0', '会员积分扩展', '', '1');

-- ----------------------------
-- Table structure for `ecm_money`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_money`;
CREATE TABLE `ecm_money` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `password` char(32) NOT NULL,
  `money` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `money_dj` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_money
-- ----------------------------
INSERT INTO `ecm_money` VALUES ('1', '1', 'e10adc3949ba59abbe56e057f20f883e', '1820.86', '0.00', '1', '1467680446');
INSERT INTO `ecm_money` VALUES ('2', '2', 'e10adc3949ba59abbe56e057f20f883e', '3996.27', '2379.00', '1', '1467680494');
INSERT INTO `ecm_money` VALUES ('3', '3', 'e10adc3949ba59abbe56e057f20f883e', '9153.00', '0.00', '1', '1468540151');
INSERT INTO `ecm_money` VALUES ('4', '21', 'e10adc3949ba59abbe56e057f20f883e', '2280.94', '380.00', '1', '1469136528');
INSERT INTO `ecm_money` VALUES ('5', '22', 'e10adc3949ba59abbe56e057f20f883e', '9876.88', '0.00', '1', '1469139501');
INSERT INTO `ecm_money` VALUES ('6', '23', 'e10adc3949ba59abbe56e057f20f883e', '8392.00', '0.00', '1', '1469139546');
INSERT INTO `ecm_money` VALUES ('8', '0', '', '0.00', '0.00', '1', '1469394421');

-- ----------------------------
-- Table structure for `ecm_money_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_money_log`;
CREATE TABLE `ecm_money_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `party_id` bigint(20) unsigned NOT NULL,
  `money` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `flow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bank_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pay_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=267 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_money_log
-- ----------------------------
INSERT INTO `ecm_money_log` VALUES ('1', '2', '0', '0.01', '1', '4', '1', '', '0', '0', '4', '1467680515');
INSERT INTO `ecm_money_log` VALUES ('2', '2', '1', '1000.00', '1', '4', '1', '', '0', '0', '0', '1467680617');
INSERT INTO `ecm_money_log` VALUES ('3', '2', '0', '0.01', '1', '4', '1', '', '0', '0', '4', '1467680750');
INSERT INTO `ecm_money_log` VALUES ('4', '2', '1', '100.00', '2', '4', '2', '', '0', '0', '0', '1467681001');
INSERT INTO `ecm_money_log` VALUES ('5', '1', '2', '100.00', '1', '4', '2', '', '0', '0', '0', '1467681001');
INSERT INTO `ecm_money_log` VALUES ('6', '2', '0', '100.00', '2', '4', '3', '', '0', '1', '0', '1467681129');
INSERT INTO `ecm_money_log` VALUES ('7', '2', '0', '100.00', '2', '4', '3', '', '0', '1', '0', '1467761785');
INSERT INTO `ecm_money_log` VALUES ('8', '2', '0', '100.00', '2', '4', '3', '', '0', '2', '0', '1467761804');
INSERT INTO `ecm_money_log` VALUES ('76', '2', '0', '100.00', '2', '1', '3', '', '0', '1', '0', '1468540835');
INSERT INTO `ecm_money_log` VALUES ('77', '3', '2', '175.00', '2', '4', '4', '', '24', '0', '0', '1469136341');
INSERT INTO `ecm_money_log` VALUES ('78', '2', '3', '0.28', '1', '4', '7', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('79', '1', '3', '0.55', '1', '4', '7', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('80', '1', '3', '13.17', '1', '4', '7', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('81', '1', '2', '0.56', '1', '4', '8', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('82', '1', '2', '13.44', '1', '4', '8', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('83', '1', '2', '7.00', '1', '4', '6', '', '0', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('84', '1', '2', '35.00', '1', '4', '5', '', '24', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('85', '2', '1', '35.00', '2', '4', '5', '', '24', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('86', '2', '3', '175.00', '1', '4', '5', '', '24', '0', '0', '1469136414');
INSERT INTO `ecm_money_log` VALUES ('87', '21', '1', '1000.00', '1', '4', '1', '', '0', '0', '0', '1469136571');
INSERT INTO `ecm_money_log` VALUES ('88', '21', '2', '175.00', '2', '4', '4', '', '25', '0', '0', '1469136737');
INSERT INTO `ecm_money_log` VALUES ('89', '2', '21', '0.28', '1', '4', '7', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('90', '1', '21', '0.55', '1', '4', '7', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('91', '1', '21', '13.17', '1', '4', '7', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('92', '1', '2', '0.56', '1', '4', '8', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('93', '1', '2', '13.44', '1', '4', '8', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('94', '1', '2', '7.00', '1', '4', '6', '', '0', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('95', '1', '2', '35.00', '1', '4', '5', '', '25', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('96', '2', '1', '35.00', '2', '4', '5', '', '25', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('97', '2', '21', '175.00', '1', '4', '5', '', '25', '0', '0', '1469137231');
INSERT INTO `ecm_money_log` VALUES ('98', '22', '1', '1000.00', '1', '4', '1', '', '0', '0', '0', '1469139616');
INSERT INTO `ecm_money_log` VALUES ('99', '23', '1', '1000.00', '1', '4', '1', '', '0', '0', '0', '1469139639');
INSERT INTO `ecm_money_log` VALUES ('100', '22', '2', '333.00', '2', '4', '4', '', '26', '0', '0', '1469140002');
INSERT INTO `ecm_money_log` VALUES ('101', '23', '2', '333.00', '2', '4', '4', '', '27', '0', '0', '1469140020');
INSERT INTO `ecm_money_log` VALUES ('102', '21', '22', '0.27', '1', '4', '7', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('103', '2', '22', '0.53', '1', '4', '7', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('104', '1', '22', '1.03', '1', '4', '7', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('105', '1', '22', '24.81', '1', '4', '7', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('106', '1', '2', '1.07', '1', '4', '8', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('107', '1', '2', '25.57', '1', '4', '8', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('108', '1', '2', '13.32', '1', '4', '6', '', '0', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('109', '1', '2', '66.60', '1', '4', '5', '', '26', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('110', '2', '1', '66.60', '2', '4', '5', '', '26', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('67', '3', '2', '175.00', '2', '4', '4', '', '23', '0', '0', '1468540209');
INSERT INTO `ecm_money_log` VALUES ('68', '2', '3', '0.28', '1', '4', '7', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('69', '1', '3', '0.55', '1', '4', '7', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('70', '1', '3', '13.17', '1', '4', '7', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('71', '1', '2', '0.56', '1', '4', '8', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('72', '1', '2', '13.44', '1', '4', '8', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('73', '1', '2', '7.00', '1', '4', '6', '', '0', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('74', '2', '1', '35.00', '2', '4', '5', '', '23', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('75', '2', '3', '175.00', '1', '4', '5', '', '23', '0', '0', '1468540262');
INSERT INTO `ecm_money_log` VALUES ('111', '2', '22', '333.00', '1', '4', '5', '', '26', '0', '0', '1469140123');
INSERT INTO `ecm_money_log` VALUES ('112', '22', '23', '0.27', '1', '4', '7', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('113', '21', '23', '0.26', '1', '4', '7', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('114', '2', '23', '0.52', '1', '4', '7', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('115', '1', '23', '25.59', '1', '4', '7', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('116', '1', '2', '1.07', '1', '4', '8', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('117', '1', '2', '25.57', '1', '4', '8', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('118', '1', '2', '13.32', '1', '4', '6', '', '0', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('119', '1', '2', '66.60', '1', '4', '5', '', '27', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('120', '2', '1', '66.60', '2', '4', '5', '', '27', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('121', '2', '23', '333.00', '1', '4', '5', '', '27', '0', '0', '1469140149');
INSERT INTO `ecm_money_log` VALUES ('122', '2', '0', '100.00', '1', '3', '1', '', '0', '0', '4', '1469146752');
INSERT INTO `ecm_money_log` VALUES ('123', '2', '0', '100.00', '1', '3', '1', '', '0', '0', '4', '1469147174');
INSERT INTO `ecm_money_log` VALUES ('124', '2', '0', '11.00', '1', '3', '1', '', '0', '0', '4', '1469147212');
INSERT INTO `ecm_money_log` VALUES ('125', '2', '0', '11.00', '1', '3', '1', '', '0', '0', '4', '1469147225');
INSERT INTO `ecm_money_log` VALUES ('126', '22', '2', '333.00', '2', '4', '4', '', '28', '0', '0', '1469147330');
INSERT INTO `ecm_money_log` VALUES ('127', '21', '22', '0.27', '1', '4', '7', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('128', '2', '22', '0.53', '1', '4', '7', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('129', '1', '22', '1.03', '1', '4', '7', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('130', '1', '22', '24.81', '1', '4', '7', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('131', '1', '2', '1.07', '1', '4', '8', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('132', '1', '2', '25.57', '1', '4', '8', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('133', '1', '2', '13.32', '1', '4', '6', '', '0', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('134', '1', '2', '66.60', '1', '4', '5', '', '28', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('135', '2', '1', '66.60', '2', '4', '5', '', '28', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('136', '2', '22', '333.00', '1', '4', '5', '', '28', '0', '0', '1469385369');
INSERT INTO `ecm_money_log` VALUES ('137', '22', '1', '10000.00', '1', '4', '1', '', '0', '0', '0', '1469385831');
INSERT INTO `ecm_money_log` VALUES ('138', '22', '2', '338.00', '2', '4', '4', '', '29', '0', '0', '1469385841');
INSERT INTO `ecm_money_log` VALUES ('139', '21', '22', '0.54', '1', '4', '7', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('140', '2', '22', '0.53', '1', '4', '7', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('141', '1', '22', '1.04', '1', '4', '7', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('142', '1', '22', '24.93', '1', '4', '7', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('143', '1', '2', '1.08', '1', '4', '8', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('144', '1', '2', '25.96', '1', '4', '8', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('145', '1', '2', '13.52', '1', '4', '6', '', '0', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('146', '1', '2', '67.60', '1', '4', '5', '', '29', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('147', '2', '1', '67.60', '2', '4', '5', '', '29', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('148', '2', '22', '338.00', '1', '4', '5', '', '29', '0', '0', '1469385996');
INSERT INTO `ecm_money_log` VALUES ('149', '22', '2', '333.00', '2', '4', '4', '', '30', '0', '0', '1469386917');
INSERT INTO `ecm_money_log` VALUES ('150', '21', '22', '0.53', '1', '4', '7', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('151', '2', '22', '0.52', '1', '4', '7', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('152', '1', '22', '1.02', '1', '4', '7', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('153', '1', '22', '24.56', '1', '4', '7', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('154', '1', '2', '1.07', '1', '4', '8', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('155', '1', '2', '25.57', '1', '4', '8', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('156', '1', '2', '13.32', '1', '4', '6', '', '0', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('157', '1', '2', '66.60', '1', '4', '5', '', '30', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('158', '2', '1', '66.60', '2', '4', '5', '', '30', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('159', '2', '22', '333.00', '1', '4', '5', '', '30', '0', '0', '1469386954');
INSERT INTO `ecm_money_log` VALUES ('160', '22', '2', '333.00', '2', '1', '4', '', '31', '0', '0', '1469388308');
INSERT INTO `ecm_money_log` VALUES ('161', '22', '21', '190.00', '2', '4', '4', '', '32', '0', '0', '1469388330');
INSERT INTO `ecm_money_log` VALUES ('162', '21', '22', '0.30', '1', '4', '7', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('163', '2', '22', '0.30', '1', '4', '7', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('164', '1', '22', '0.58', '1', '4', '7', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('165', '1', '22', '14.01', '1', '4', '7', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('166', '2', '21', '0.30', '1', '4', '8', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('167', '1', '21', '0.60', '1', '4', '8', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('168', '1', '21', '14.30', '1', '4', '8', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('169', '1', '21', '7.60', '1', '4', '6', '', '0', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('170', '1', '21', '38.00', '1', '4', '5', '', '32', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('171', '21', '1', '38.00', '2', '4', '5', '', '32', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('172', '21', '22', '190.00', '1', '4', '5', '', '32', '0', '0', '1469388424');
INSERT INTO `ecm_money_log` VALUES ('173', '3', '2', '693.00', '2', '3', '4', '', '37', '0', '0', '1469396974');
INSERT INTO `ecm_money_log` VALUES ('174', '3', '2', '500.00', '1', '4', '9', '', '0', '0', '0', '1469398168');
INSERT INTO `ecm_money_log` VALUES ('175', '2', '3', '193.00', '1', '4', '9', '', '0', '0', '0', '1469398168');
INSERT INTO `ecm_money_log` VALUES ('176', '3', '2', '500.00', '1', '4', '9', '', '0', '0', '0', '1469398289');
INSERT INTO `ecm_money_log` VALUES ('177', '2', '3', '193.00', '1', '4', '9', '', '0', '0', '0', '1469398289');
INSERT INTO `ecm_money_log` VALUES ('178', '22', '2', '333.00', '1', '4', '9', '', '0', '0', '0', '1469398877');
INSERT INTO `ecm_money_log` VALUES ('179', '3', '2', '175.00', '2', '3', '4', '', '2', '0', '0', '1469411560');
INSERT INTO `ecm_money_log` VALUES ('180', '21', '22', '0.53', '1', '4', '7', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('181', '2', '22', '0.52', '1', '4', '7', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('182', '1', '22', '1.02', '1', '4', '7', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('183', '1', '22', '24.56', '1', '4', '7', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('184', '1', '2', '1.07', '1', '4', '8', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('185', '1', '2', '25.57', '1', '4', '8', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('186', '1', '2', '13.32', '1', '4', '6', '', '0', '0', '0', '1469411882');
INSERT INTO `ecm_money_log` VALUES ('187', '21', '22', '0.30', '1', '4', '7', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('188', '2', '22', '0.30', '1', '4', '7', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('189', '1', '22', '0.58', '1', '4', '7', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('190', '1', '22', '14.01', '1', '4', '7', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('191', '2', '21', '0.30', '1', '4', '8', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('192', '1', '21', '0.60', '1', '4', '8', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('193', '1', '21', '14.30', '1', '4', '8', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('194', '1', '21', '7.60', '1', '4', '6', '', '0', '0', '0', '1469411927');
INSERT INTO `ecm_money_log` VALUES ('195', '3', '2', '133.00', '2', '4', '4', '', '9', '0', '0', '1469490365');
INSERT INTO `ecm_money_log` VALUES ('196', '21', '2', '174.00', '2', '3', '4', '', '10', '0', '0', '1469490507');
INSERT INTO `ecm_money_log` VALUES ('197', '2', '3', '133.00', '1', '4', '5', '', '9', '0', '0', '1469490950');
INSERT INTO `ecm_money_log` VALUES ('198', '2', '3', '104.00', '1', '4', '5', '', '4', '0', '0', '1469491110');
INSERT INTO `ecm_money_log` VALUES ('199', '3', '2', '266.00', '2', '4', '4', '', '11', '0', '0', '1469491218');
INSERT INTO `ecm_money_log` VALUES ('200', '2', '3', '266.00', '1', '4', '5', '', '11', '0', '0', '1469491256');
INSERT INTO `ecm_money_log` VALUES ('201', '2', '3', '266.00', '1', '4', '5', '', '11', '0', '0', '1469491313');
INSERT INTO `ecm_money_log` VALUES ('202', '2', '3', '215.82', '1', '4', '5', '', '11', '0', '0', '1469491339');
INSERT INTO `ecm_money_log` VALUES ('203', '2', '3', '215.82', '1', '4', '5', '', '11', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('204', '2', '3', '0.40', '1', '4', '7', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('205', '1', '3', '0.79', '1', '4', '7', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('206', '1', '3', '18.88', '1', '4', '7', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('207', '1', '2', '0.80', '1', '4', '8', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('208', '1', '2', '19.27', '1', '4', '8', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('209', '1', '2', '10.04', '1', '4', '6', '', '0', '0', '0', '1469491354');
INSERT INTO `ecm_money_log` VALUES ('210', '21', '0', '0.01', '1', '3', '1', '', '0', '0', '4', '1469668365');
INSERT INTO `ecm_money_log` VALUES ('211', '21', '22', '400.00', '2', '4', '2', '', '0', '0', '0', '1469668582');
INSERT INTO `ecm_money_log` VALUES ('212', '22', '21', '400.00', '1', '4', '2', '', '0', '0', '0', '1469668582');
INSERT INTO `ecm_money_log` VALUES ('213', '21', '0', '100.00', '2', '4', '3', '', '0', '5', '0', '1469668692');
INSERT INTO `ecm_money_log` VALUES ('214', '3', '2', '1705.00', '2', '3', '4', 'wallet', '12', '0', '0', '1469754023');
INSERT INTO `ecm_money_log` VALUES ('215', '3', '21', '190.00', '2', '3', '4', 'wallet', '13', '0', '0', '1469754023');
INSERT INTO `ecm_money_log` VALUES ('216', '3', '0', '100.00', '1', '3', '1', '', '0', '0', '4', '1470609498');
INSERT INTO `ecm_money_log` VALUES ('217', '3', '2', '208.00', '2', '3', '4', 'wallet', '18', '0', '0', '1470693085');
INSERT INTO `ecm_money_log` VALUES ('218', '3', '0', '0.01', '1', '3', '1', '', '0', '0', '4', '1470865537');
INSERT INTO `ecm_money_log` VALUES ('219', '3', '2', '133.00', '2', '3', '4', 'wallet', '25', '0', '0', '1471027364');
INSERT INTO `ecm_money_log` VALUES ('220', '3', '21', '190.00', '2', '3', '4', 'wallet', '26', '0', '0', '1471027364');
INSERT INTO `ecm_money_log` VALUES ('221', '3', '21', '190.00', '2', '3', '4', 'wallet', '28', '0', '0', '1471027662');
INSERT INTO `ecm_money_log` VALUES ('222', '3', '2', '333.00', '2', '3', '4', 'wallet', '29', '0', '0', '1471027662');
INSERT INTO `ecm_money_log` VALUES ('223', '23', '21', '190.00', '2', '4', '4', 'wallet', '31', '0', '0', '1471459372');
INSERT INTO `ecm_money_log` VALUES ('224', '21', '23', '152.20', '1', '4', '5', '', '31', '0', '0', '1471459401');
INSERT INTO `ecm_money_log` VALUES ('225', '23', '21', '190.00', '2', '4', '4', 'wallet', '32', '0', '0', '1471459697');
INSERT INTO `ecm_money_log` VALUES ('226', '21', '23', '152.20', '1', '4', '5', '', '32', '0', '0', '1471459728');
INSERT INTO `ecm_money_log` VALUES ('227', '23', '21', '190.00', '2', '4', '4', 'wallet', '33', '0', '0', '1471459768');
INSERT INTO `ecm_money_log` VALUES ('228', '21', '23', '152.20', '1', '4', '5', '', '33', '0', '0', '1471459823');
INSERT INTO `ecm_money_log` VALUES ('229', '21', '23', '152.20', '1', '4', '5', '', '33', '0', '0', '1471460005');
INSERT INTO `ecm_money_log` VALUES ('230', '23', '1', '10000.00', '1', '4', '1', '', '0', '0', '0', '1471462618');
INSERT INTO `ecm_money_log` VALUES ('231', '23', '21', '1135.00', '2', '4', '4', 'wallet', '34', '0', '0', '1471462631');
INSERT INTO `ecm_money_log` VALUES ('232', '21', '23', '908.20', '1', '4', '5', '', '34', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('233', '22', '23', '1.81', '1', '4', '7', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('234', '21', '23', '0.89', '1', '4', '7', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('235', '2', '23', '1.76', '1', '4', '7', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('236', '1', '23', '86.26', '1', '4', '7', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('237', '2', '21', '3.63', '1', '4', '8', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('238', '1', '21', '5.23', '1', '4', '8', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('239', '1', '21', '81.87', '1', '4', '8', '', '0', '0', '0', '1471462693');
INSERT INTO `ecm_money_log` VALUES ('240', '23', '21', '190.00', '2', '4', '4', 'wallet', '35', '0', '0', '1471462757');
INSERT INTO `ecm_money_log` VALUES ('241', '21', '23', '152.20', '1', '4', '5', '', '35', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('242', '22', '23', '0.60', '1', '4', '7', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('243', '21', '23', '0.15', '1', '4', '7', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('244', '2', '23', '0.29', '1', '4', '7', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('245', '1', '23', '14.08', '1', '4', '7', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('246', '2', '21', '0.60', '1', '4', '8', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('247', '1', '21', '0.87', '1', '4', '8', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('248', '1', '21', '13.64', '1', '4', '8', '', '0', '0', '0', '1471462797');
INSERT INTO `ecm_money_log` VALUES ('249', '23', '21', '190.00', '2', '4', '4', 'wallet', '36', '0', '0', '1471462875');
INSERT INTO `ecm_money_log` VALUES ('250', '21', '23', '152.20', '1', '4', '5', '', '36', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('251', '22', '23', '0.60', '1', '4', '7', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('252', '21', '23', '0.15', '1', '4', '7', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('253', '2', '23', '0.29', '1', '4', '7', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('254', '1', '23', '14.08', '1', '4', '7', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('255', '2', '21', '0.60', '1', '4', '8', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('256', '1', '21', '0.87', '1', '4', '8', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('257', '1', '21', '13.64', '1', '4', '8', '', '0', '0', '0', '1471462931');
INSERT INTO `ecm_money_log` VALUES ('258', '23', '21', '190.00', '2', '4', '4', 'wallet', '37', '0', '0', '1471463249');
INSERT INTO `ecm_money_log` VALUES ('259', '21', '23', '152.20', '1', '4', '5', '', '37', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('260', '22', '23', '0.60', '1', '4', '7', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('261', '21', '23', '0.15', '1', '4', '7', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('262', '2', '23', '0.29', '1', '4', '7', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('263', '1', '23', '14.08', '1', '4', '7', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('264', '2', '21', '0.60', '1', '4', '8', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('265', '1', '21', '0.87', '1', '4', '8', '', '0', '0', '0', '1471463278');
INSERT INTO `ecm_money_log` VALUES ('266', '1', '21', '13.64', '1', '4', '8', '', '0', '0', '0', '1471463278');

-- ----------------------------
-- Table structure for `ecm_msg`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_msg`;
CREATE TABLE `ecm_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `functions` varchar(255) DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_msg
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_msglog`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_msglog`;
CREATE TABLE `ecm_msglog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) DEFAULT NULL,
  `to_mobile` varchar(100) DEFAULT NULL,
  `content` text,
  `state` varchar(100) DEFAULT NULL,
  `type` int(10) unsigned DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_msglog
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_navigation`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_navigation`;
CREATE TABLE `ecm_navigation` (
  `nav_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `open_new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hot` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_navigation
-- ----------------------------
INSERT INTO `ecm_navigation` VALUES ('1', 'header', '男装', 'index.php?app=search&cate_id=1', '255', '0', '0');

-- ----------------------------
-- Table structure for `ecm_order`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_order`;
CREATE TABLE `ecm_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT 'material',
  `extension` varchar(10) NOT NULL DEFAULT '',
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0',
  `seller_name` varchar(100) DEFAULT NULL,
  `buyer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `payment_name` varchar(100) DEFAULT NULL,
  `payment_code` varchar(20) NOT NULL DEFAULT '',
  `out_trade_sn` varchar(20) NOT NULL DEFAULT '',
  `pay_time` int(10) unsigned DEFAULT NULL,
  `pay_message` varchar(255) NOT NULL DEFAULT '',
  `ship_time` int(10) unsigned DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `express_company` varchar(50) NOT NULL,
  `finished_time` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `evaluation_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `evaluation_time` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `postscript` varchar(255) NOT NULL DEFAULT '',
  `pay_alter` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `trans_id` int(11) DEFAULT '0',
  `order_merge` smallint(5) DEFAULT NULL,
  `order_sns` varchar(266) DEFAULT NULL,
  `flag` int(1) NOT NULL,
  `memo` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `order_sn` (`order_sn`,`seller_id`) USING BTREE,
  KEY `seller_name` (`seller_name`) USING BTREE,
  KEY `buyer_name` (`buyer_name`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_order
-- ----------------------------
INSERT INTO `ecm_order` VALUES ('1', '1620650774', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '11', '1469411369', '4', '支付宝', 'alipay', '1620650774', null, '', null, null, '', '0', '150.00', '0.00', '0.00', '155.00', '0', '0', '0', '', '1', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('2', '1620696587', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '20', '1469411544', '0', '钱包支付', 'wallet', '1620696587', '1469411560', '', null, null, '', '0', '170.00', '0.00', '0.00', '175.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('3', '1620661176', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '20', '1469411699', '0', '钱包支付', 'wallet', '1620661176', '1469411769', '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('4', '1620693985', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '40', '1469411699', '0', '钱包支付', 'wallet', '1620693985', '1469411769', '', '1469491090', '1620693985', '', '1469491110', '99.00', '0.00', '0.00', '104.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('5', '1620686587', 'material', 'normal', '0', null, '0', null, '', '11', '1469411699', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '294.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:3;s:10:\"1620661176\";i:4;s:10:\"1620693985\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('6', '1620643158', 'material', 'normal', '2', '演示店铺', '22', 'tiantian', 'tiantian@sina.cn', '40', '1469411745', '0', '钱包支付', 'wallet', '1620643158', '1469411758', '', '1469411863', '刚吃饭虚报从', '', '1469411882', '328.00', '0.00', '0.00', '333.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('7', '1620601237', 'material', 'normal', '21', '冰之渴望', '22', 'tiantian', 'tiantian@sina.cn', '40', '1469411745', '0', '钱包支付', 'wallet', '1620601237', '1469411758', '', '1469411902', '我已有图图', '', '1469411927', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('8', '1620641300', 'material', 'normal', '0', null, '0', null, '', '11', '1469411745', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '523.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:6;s:10:\"1620643158\";i:7;s:10:\"1620601237\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('9', '1620750367', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '40', '1469483923', '0', '钱包支付', 'wallet', '1620750367', '1469490365', '', '1469490401', '1620750367', '', '1470848709', '128.00', '0.00', '0.00', '133.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('10', '1620711498', 'material', 'normal', '2', '演示店铺', '21', 'summer', 'summer@sina.com', '40', '1469490464', '0', '钱包支付', 'wallet', '1620711498', '1469490507', '', '1469490523', '5465456', '', '1469490560', '169.00', '0.00', '0.00', '174.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('11', '1620775728', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '40', '1469491207', '0', '钱包支付', 'wallet', '1620775728', '1469491218', '', '1469491240', '1620775728', '', '1469491354', '256.00', '0.00', '0.00', '266.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('12', '1621019632', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '20', '1469753736', '0', '钱包支付', 'wallet', '1621019632', '1469754023', '', null, null, '', '0', '1640.00', '0.00', '0.00', '1705.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('13', '1621096132', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '20', '1469753736', '0', '钱包支付', 'wallet', '1621096132', '1469754023', '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('14', '1621079160', 'material', 'normal', '0', null, '0', null, '', '11', '1469753736', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '1895.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:12;s:10:\"1621019632\";i:13;s:10:\"1621096132\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('15', '1622051740', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '11', '1470597232', null, null, '', '', null, '', null, null, '', '0', '605.00', '0.00', '0.00', '635.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('16', '1622079481', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '11', '1470597232', null, null, '', '', null, '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('17', '1622028135', 'material', 'normal', '0', null, '0', null, '', '11', '1470597232', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '825.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:15;s:10:\"1622051740\";i:16;s:10:\"1622079481\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('18', '1622055997', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '0', '1470597534', '0', '钱包支付', 'wallet', '1622055997', '1470693085', '', '1470693126', '1622055997', '', '0', '188.00', '0.00', '0.00', '208.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('19', '1622033098', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '11', '1470597534', null, null, '', '', null, '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('20', '1622023803', 'material', 'normal', '0', null, '0', null, '', '11', '1470597534', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '398.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:18;s:10:\"1622055997\";i:19;s:10:\"1622033098\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('21', '1622486129', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '11', '1470938493', null, null, '', '', null, '', null, null, '', '0', '256.00', '0.00', '0.00', '266.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('22', '1622481187', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '11', '1470938493', null, null, '', '', null, '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('23', '1622470551', 'material', 'normal', '0', null, '0', null, '', '11', '1470938493', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '456.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:21;s:10:\"1622486129\";i:22;s:10:\"1622481187\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('24', '1622428287', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '11', '1470938837', null, null, '', '', null, '', null, null, '', '0', '328.00', '0.00', '0.00', '333.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('25', '1622543166', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '20', '1471026097', '0', '钱包支付', 'wallet', '1622543166', '1471027364', '', null, null, '', '0', '128.00', '0.00', '0.00', '133.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('26', '1622579875', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '20', '1471026097', '0', '钱包支付', 'wallet', '1622579875', '1471027364', '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('27', '1622527242', 'material', 'normal', '0', null, '0', null, '', '11', '1471026097', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '323.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:25;s:10:\"1622543166\";i:26;s:10:\"1622579875\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('28', '1622508621', 'material', 'normal', '21', '冰之渴望', '3', 'buyer', 'buyer@ecmall.com', '20', '1471026169', '0', '钱包支付', 'wallet', '1622508621', '1471027662', '', null, null, '', '0', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('29', '1622514347', 'material', 'normal', '2', '演示店铺', '3', 'buyer', 'buyer@ecmall.com', '20', '1471026169', '0', '钱包支付', 'wallet', '1622514347', '1471027662', '', null, null, '', '0', '328.00', '0.00', '0.00', '333.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('30', '1622527984', 'material', 'normal', '0', null, '0', null, '', '11', '1471026169', null, null, '', '', null, '', null, null, '', '0', '0.00', '0.00', '0.00', '523.00', '0', '0', '0', '', '0', '0', '1', 'a:2:{i:28;s:10:\"1622508621\";i:29;s:10:\"1622514347\";}', '0', '');
INSERT INTO `ecm_order` VALUES ('31', '1623099421', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471459360', '0', '钱包支付', 'wallet', '1623099421', '1471459372', '', '1471459387', '123456', '', '1471459401', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('32', '1623071838', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '30', '1471459686', '0', '钱包支付', 'wallet', '1623071838', '1471459697', '', '1471459713', '123456', '', '1471459728', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('33', '1623073260', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471459757', '0', '钱包支付', 'wallet', '1623073260', '1471459768', '', '1471459799', '122122', '', '1471460005', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('34', '1623055018', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471462576', '0', '钱包支付', 'wallet', '1623055018', '1471462631', '', '1471462678', '111111', '', '1471462693', '1134.00', '0.00', '0.00', '1135.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('35', '1623010820', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471462745', '0', '钱包支付', 'wallet', '1623010820', '1471462757', '', '1471462779', '12121211', '', '1471462797', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('36', '1623085834', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471462861', '0', '钱包支付', 'wallet', '1623085834', '1471462875', '', '1471462892', '12121', '', '1471462931', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');
INSERT INTO `ecm_order` VALUES ('37', '1623023319', 'material', 'normal', '21', '冰之渴望', '23', 'lucky', 'lucky@sina.cn', '40', '1471463237', '0', '钱包支付', 'wallet', '1623023319', '1471463249', '', '1471463267', '7675767', '', '1471463278', '189.00', '0.00', '0.00', '190.00', '0', '0', '0', '', '0', '0', null, null, '0', '');

-- ----------------------------
-- Table structure for `ecm_order_extm`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_order_extm`;
CREATE TABLE `ecm_order_extm` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `shipping_id` int(10) unsigned DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`order_id`),
  KEY `consignee` (`consignee`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_order_extm
-- ----------------------------
INSERT INTO `ecm_order_extm` VALUES ('1', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('2', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('3', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, 'changtong', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('4', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('6', 'tiantian', '133', '中国	辽宁省	本溪', 'bzbvcbcvbcbv', '', '', '36456345654645645', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('7', 'tiantian', '133', '中国	辽宁省	本溪', 'bzbvcbcvbcbv', '', '', '36456345654645645', null, 'changtong', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('9', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('10', 'summer', '44', '中国	上海市	长宁区', '上海市长宁区', '123456', '', '15882243695', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('11', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '10.00');
INSERT INTO `ecm_order_extm` VALUES ('12', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '65.00');
INSERT INTO `ecm_order_extm` VALUES ('13', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('15', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '快递', '30.00');
INSERT INTO `ecm_order_extm` VALUES ('16', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('18', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, 'EMS', '20.00');
INSERT INTO `ecm_order_extm` VALUES ('19', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('21', 'dddddddd', '358', '中国	四川省	成都', 'dasdsada', '610000', '', '15900000000', null, '平邮', '10.00');
INSERT INTO `ecm_order_extm` VALUES ('22', 'dddddddd', '358', '中国	四川省	成都', 'dasdsada', '610000', '', '15900000000', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('24', '哈哈1', '104', '中国	河北省', '放松放松的', '000000', '', '15822222222', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('25', 'dddddddd', '358', '中国	四川省	成都', 'dasdsada', '610000', '', '15900000000', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('26', 'dddddddd', '358', '中国	四川省	成都', 'dasdsada', '610000', '', '15900000000', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('28', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('29', '超级卖家', '43', '中国	上海市	徐汇区', '长江路15号', '200088', '021-88886666', '13366669999', null, '平邮', '5.00');
INSERT INTO `ecm_order_extm` VALUES ('31', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('32', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('33', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('34', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('35', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('36', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');
INSERT INTO `ecm_order_extm` VALUES ('37', 'lucky', '63', '中国	重庆市	大渡口', '发斯蒂芬斯蒂芬的广泛的施工方', '', '', '453453453453454', null, '哈哈', '1.00');

-- ----------------------------
-- Table structure for `ecm_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_order_goods`;
CREATE TABLE `ecm_order_goods` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `spec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `specification` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `goods_image` varchar(255) DEFAULT NULL,
  `evaluation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL DEFAULT '',
  `credit_value` tinyint(1) NOT NULL DEFAULT '0',
  `is_valid` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reply_content` text NOT NULL,
  `reply_time` int(10) NOT NULL,
  `shipped_evaluation` decimal(4,2) NOT NULL,
  `service_evaluation` decimal(4,2) NOT NULL,
  `goods_evaluation` decimal(4,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`,`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_order_goods
-- ----------------------------
INSERT INTO `ecm_order_goods` VALUES ('1', '1', '21', '09春季新款简约大方高雅修身针织连衣裙983配腰带', '56', '', '170.00', '1', 'data/files/store_2/goods_25/small_200908060950258122.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('2', '2', '21', '09春季新款简约大方高雅修身针织连衣裙983配腰带', '56', '', '170.00', '1', 'data/files/store_2/goods_25/small_200908060950258122.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('3', '3', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('4', '4', '1', '多彩人生多彩裤', '1', '颜色:粉红色 尺码:XL', '99.00', '1', 'data/files/store_2/goods_179/small_200908060822598478.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('5', '6', '29', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '74', '', '328.00', '1', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('6', '7', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('7', '9', '25', '春款韩版卡其休闲上衣', '63', '颜色:灰色 尺码:S', '128.00', '1', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('8', '10', '24', '阿迪达斯花式运动鞋', '60', '颜色:花色 尺码:37', '169.00', '1', 'data/files/store_2/goods_20/small_200908060957002218.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('9', '11', '25', '春款韩版卡其休闲上衣', '63', '颜色:灰色 尺码:S', '128.00', '2', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('10', '12', '16', '横纹方格运动鞋', '42', '颜色:方格混色 尺码:37', '128.00', '1', 'data/files/store_2/goods_67/small_200908060927474675.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('11', '12', '16', '横纹方格运动鞋', '44', '颜色:方格白色 尺码:39', '126.00', '12', 'data/files/store_2/goods_67/small_200908060927474675.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('12', '13', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('13', '15', '29', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '74', '', '328.00', '1', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('14', '15', '26', '喜皮风格牛仔短裤', '67', '', '89.00', '1', 'data/files/store_2/goods_47/small_200908061000474424.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('15', '15', '2', '花色高邦运动鞋', '4', '颜色:混色 尺码:38', '188.00', '1', 'data/files/store_2/goods_131/small_200908060828517782.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('16', '16', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('17', '18', '2', '花色高邦运动鞋', '4', '颜色:混色 尺码:38', '188.00', '1', 'data/files/store_2/goods_131/small_200908060828517782.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('18', '19', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('19', '21', '25', '春款韩版卡其休闲上衣', '66', '颜色:灰色 尺码:XL', '128.00', '1', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('20', '21', '25', '春款韩版卡其休闲上衣', '63', '颜色:灰色 尺码:S', '128.00', '1', 'data/files/store_2/goods_139/small_200908060958592106.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('21', '22', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('22', '24', '29', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '74', '', '328.00', '1', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('23', '25', '16', '横纹方格运动鞋', '42', '颜色:方格混色 尺码:37', '128.00', '1', 'data/files/store_2/goods_67/small_200908060927474675.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('24', '26', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('25', '28', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('26', '29', '29', '夹克.韩版新款09开衫小外套卫衣短甜美显瘦春装', '74', '', '328.00', '1', 'data/files/store_2/goods_121/small_200908061008412008.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('27', '31', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('28', '32', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('29', '33', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('30', '34', '39', '测试商品1', '92', '', '189.00', '6', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('31', '35', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('32', '36', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_order_goods` VALUES ('33', '37', '39', '测试商品1', '92', '', '189.00', '1', 'data/files/store_21/goods_94/small_201607251121348958.jpg', '0', '', '0', '1', '', '0', '0.00', '0.00', '0.00', '');

-- ----------------------------
-- Table structure for `ecm_order_integral`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_order_integral`;
CREATE TABLE `ecm_order_integral` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `frozen_integral` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_order_integral
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_order_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_order_log`;
CREATE TABLE `ecm_order_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `operator` varchar(60) NOT NULL DEFAULT '',
  `order_status` varchar(60) NOT NULL DEFAULT '',
  `changed_status` varchar(60) NOT NULL DEFAULT '',
  `remark` varchar(255) DEFAULT NULL,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_order_log
-- ----------------------------
INSERT INTO `ecm_order_log` VALUES ('1', '6', 'seller', '待发货', '已发货', '', '1469411863');
INSERT INTO `ecm_order_log` VALUES ('2', '6', 'tiantian', '已发货', '已完成', '买家确认收货', '1469411882');
INSERT INTO `ecm_order_log` VALUES ('3', '7', 'summer', '待发货', '已发货', '', '1469411902');
INSERT INTO `ecm_order_log` VALUES ('4', '7', 'tiantian', '已发货', '已完成', '买家确认收货', '1469411927');
INSERT INTO `ecm_order_log` VALUES ('5', '9', 'seller', '待发货', '已发货', '', '1469490401');
INSERT INTO `ecm_order_log` VALUES ('6', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490418');
INSERT INTO `ecm_order_log` VALUES ('7', '10', 'seller', '待发货', '已发货', '', '1469490523');
INSERT INTO `ecm_order_log` VALUES ('8', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490553');
INSERT INTO `ecm_order_log` VALUES ('9', '10', 'summer', '已发货', '已完成', '买家确认收货', '1469490560');
INSERT INTO `ecm_order_log` VALUES ('10', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490702');
INSERT INTO `ecm_order_log` VALUES ('11', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490788');
INSERT INTO `ecm_order_log` VALUES ('12', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490814');
INSERT INTO `ecm_order_log` VALUES ('13', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490876');
INSERT INTO `ecm_order_log` VALUES ('14', '9', 'buyer', '已发货', '已完成', '买家确认收货', '1469490950');
INSERT INTO `ecm_order_log` VALUES ('15', '4', 'seller', '待发货', '已发货', '', '1469491090');
INSERT INTO `ecm_order_log` VALUES ('16', '4', 'buyer', '已发货', '已完成', '买家确认收货', '1469491110');
INSERT INTO `ecm_order_log` VALUES ('17', '11', 'seller', '待发货', '已发货', '', '1469491240');
INSERT INTO `ecm_order_log` VALUES ('18', '11', 'buyer', '已发货', '已完成', '买家确认收货', '1469491256');
INSERT INTO `ecm_order_log` VALUES ('19', '11', 'buyer', '已发货', '已完成', '买家确认收货', '1469491313');
INSERT INTO `ecm_order_log` VALUES ('20', '11', 'buyer', '已发货', '已完成', '买家确认收货', '1469491339');
INSERT INTO `ecm_order_log` VALUES ('21', '11', 'buyer', '已发货', '已完成', '买家确认收货', '1469491354');
INSERT INTO `ecm_order_log` VALUES ('22', '1', 'seller', '待付款', '待付款', '调整费用', '1469576272');
INSERT INTO `ecm_order_log` VALUES ('23', '18', 'seller', '待发货', '已发货', '11', '1470693126');
INSERT INTO `ecm_order_log` VALUES ('24', '9', '0', '已发货', '已完成', '', '1470848709');
INSERT INTO `ecm_order_log` VALUES ('25', '31', 'summer', '待发货', '已发货', '', '1471459387');
INSERT INTO `ecm_order_log` VALUES ('26', '31', 'lucky', '已发货', '已完成', '买家确认收货', '1471459401');
INSERT INTO `ecm_order_log` VALUES ('27', '32', 'summer', '待发货', '已发货', '', '1471459713');
INSERT INTO `ecm_order_log` VALUES ('28', '32', 'lucky', '已发货', '已完成', '买家确认收货', '1471459728');
INSERT INTO `ecm_order_log` VALUES ('29', '33', 'summer', '待发货', '已发货', '', '1471459799');
INSERT INTO `ecm_order_log` VALUES ('30', '33', 'lucky', '已发货', '已完成', '买家确认收货', '1471459823');
INSERT INTO `ecm_order_log` VALUES ('31', '33', 'lucky', '已发货', '已完成', '买家确认收货', '1471460005');
INSERT INTO `ecm_order_log` VALUES ('32', '34', 'summer', '待发货', '已发货', '', '1471462678');
INSERT INTO `ecm_order_log` VALUES ('33', '34', 'lucky', '已发货', '已完成', '买家确认收货', '1471462693');
INSERT INTO `ecm_order_log` VALUES ('34', '35', 'summer', '待发货', '已发货', '', '1471462779');
INSERT INTO `ecm_order_log` VALUES ('35', '35', 'lucky', '已发货', '已完成', '买家确认收货', '1471462797');
INSERT INTO `ecm_order_log` VALUES ('36', '36', 'summer', '待发货', '已发货', '', '1471462892');
INSERT INTO `ecm_order_log` VALUES ('37', '36', 'lucky', '已发货', '已完成', '买家确认收货', '1471462931');
INSERT INTO `ecm_order_log` VALUES ('38', '37', 'summer', '待发货', '已发货', '', '1471463267');
INSERT INTO `ecm_order_log` VALUES ('39', '37', 'lucky', '已发货', '已完成', '买家确认收货', '1471463278');

-- ----------------------------
-- Table structure for `ecm_pageview`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_pageview`;
CREATE TABLE `ecm_pageview` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `view_date` date NOT NULL DEFAULT '0000-00-00',
  `view_times` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`rec_id`),
  UNIQUE KEY `storedate` (`store_id`,`view_date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_pageview
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_partner`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_partner`;
CREATE TABLE `ecm_partner` (
  `partner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`partner_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_partner
-- ----------------------------
INSERT INTO `ecm_partner` VALUES ('2', '2', 'ECMall', 'http://ecmall.shopex.cn', null, '1');
INSERT INTO `ecm_partner` VALUES ('3', '2', 'ECShop', 'http://www.ecshop.com', null, '2');
INSERT INTO `ecm_partner` VALUES ('4', '0', '上海商派', 'http://www.shopex.cn', 'data/files/mall/partner/4.png', '1');
INSERT INTO `ecm_partner` VALUES ('5', '0', '支付宝', 'http://www.alipay.com', 'data/files/mall/partner/5.gif', '2');
INSERT INTO `ecm_partner` VALUES ('6', '0', '财付通', 'http://www.tenpay.com', 'data/files/mall/partner/6.PNG', '3');
INSERT INTO `ecm_partner` VALUES ('7', '21', '卓流官网', 'http://www.360cd.cn', null, '255');

-- ----------------------------
-- Table structure for `ecm_payment`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_payment`;
CREATE TABLE `ecm_payment` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_code` varchar(20) NOT NULL DEFAULT '',
  `payment_name` varchar(100) NOT NULL DEFAULT '',
  `payment_desc` varchar(255) DEFAULT NULL,
  `config` text,
  `is_online` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `cod_regions` text NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `payment_code` (`payment_code`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_payment
-- ----------------------------
INSERT INTO `ecm_payment` VALUES ('9', '21', 'alipay', '支付宝', '', 'a:5:{s:14:\"alipay_account\";s:0:\"\";s:10:\"alipay_key\";s:0:\"\";s:14:\"alipay_partner\";s:0:\"\";s:14:\"alipay_service\";s:21:\"trade_create_by_buyer\";s:5:\"pcode\";s:0:\"\";}', '1', '1', '0', '');
INSERT INTO `ecm_payment` VALUES ('4', '0', 'alipay', '支付宝', '', 'a:5:{s:14:\"alipay_account\";s:18:\"liubowater@126.com\";s:10:\"alipay_key\";s:32:\"3w77y2r0oislawiuv1k6c9r61ajl7yxk\";s:14:\"alipay_partner\";s:16:\"2088002129928641\";s:14:\"alipay_service\";s:25:\"create_direct_pay_by_user\";s:5:\"pcode\";s:0:\"\";}', '1', '1', '0', '');
INSERT INTO `ecm_payment` VALUES ('5', '0', 'bank', '银行汇款', '', '', '0', '1', '0', '');
INSERT INTO `ecm_payment` VALUES ('6', '0', 'cod', '货到付款', '', '', '0', '1', '0', '');
INSERT INTO `ecm_payment` VALUES ('7', '0', 'tenpay2', '财付通即时到帐', '', 'a:4:{s:14:\"tenpay_account\";s:0:\"\";s:10:\"tenpay_key\";s:0:\"\";s:12:\"magic_string\";s:0:\"\";s:5:\"pcode\";s:0:\"\";}', '1', '1', '0', '');

-- ----------------------------
-- Table structure for `ecm_point_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_point_goods`;
CREATE TABLE `ecm_point_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` char(200) DEFAULT NULL,
  `goods_desc` char(250) DEFAULT NULL,
  `goods_content` text,
  `need_point` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `default_image` varchar(500) DEFAULT NULL,
  `used_stock` int(11) DEFAULT NULL,
  `goods_price` decimal(10,2) DEFAULT NULL,
  `max_num` int(11) DEFAULT NULL,
  `point_type` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_point_goods
-- ----------------------------
INSERT INTO `ecm_point_goods` VALUES ('8', 'VIP套餐（牛蒡酱8瓶）', '牛蒡含有一种广效的抗癌物质———牛蒡酚；还含有一种非常特殊的养分———菊糖（适合糖尿病人食用），是一种可促进性荷尔蒙分泌的精氨酸，有助于人体筋骨发达，增强体力及壮阳。', '<p><span style=\"font-size: large;\"><span style=\"color: #ff0000;\">&nbsp;&nbsp;&nbsp;&nbsp; 牛蒡在我国以前多为药用，而在日本是家家户户日常蔬菜，含有丰富的维生素和矿物质。其胡萝卜素含量在蔬菜中居第二位；蛋白质、钙和植物纤维的含量为根类食物之首。植物纤维有助清除体内垃圾，故誉为&ldquo;大自然最佳清血剂&rdquo;；木质素是牛蒡所含最多的植物纤维的一种，具有十分突出的抗菌作用。牛蒡含有一种广效的抗癌物质&mdash;&mdash;&mdash;牛蒡酚；还含有一种非常特殊的养分&mdash;&mdash;&mdash;菊糖（适合糖尿病人食用），是一种可促进性荷尔蒙分泌的精氨酸，有助于人体筋骨发达，增强体力及壮阳。牛蒡泡茶，色泽金黄、香味宜人、价比黄金，故在台南称黄金牛蒡茶。牛蒡为我国古老的药食两用食物蔬菜，牛蒡可每日食用而无任何副作用，且对体内各系统的平衡具有复原功能。</span></span></p>', '100', '359', null, '0', '1', 'data/files/mall/common/201411110856109460.jpg', '53', '112.00', '0', '0');
INSERT INTO `ecm_point_goods` VALUES ('9', '养元面(100积分1件9种面组成）', '有9种面条组成', '<p><span style=\"font-size: large;\"><span style=\"color: #800000;\">牛蒡面、茯苓面、山药面、紫薯面、木瓜面、红枣面、芦荟面、银杏面、核桃面。</span></span></p>', '100', '65', null, '0', '1', 'data/files/mall/common/201501171642163419.jpg', '651', '118.00', '0', '0');
INSERT INTO `ecm_point_goods` VALUES ('10', '商务会员套餐', '商务会员套餐产品（310元礼盒蔬菜汤1盒，50元牛蒡干1包,4瓶牛蒡酱，3包牛蒡酥）。此商务会员可以申请家庭库存代理商。', '<p class=\"MsoNormal\" style=\"text-transform: none; background-color: #ffffff; text-indent: 10.5pt; margin: 16px 0px; font: 14px/28px Arial, 微软雅黑; white-space: normal; letter-spacing: normal; color: #000000; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\"><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">!、牛蒡酥在传统牛蒡酥的基础上进行大胆改进，挑选牛蒡片、蛋苕丝（糯米粉、红薯、鸡蛋）、白砂糖、麦芽糖、芝麻等营养、健康的原料精心配比秘制而成。具有牛蒡多更酥脆、更营养的特点。</span></p>\r\n<p class=\"MsoNormal\" style=\"text-transform: none; background-color: #ffffff; text-indent: 10.5pt; margin: 16px 0px; font: 14px/28px Arial, 微软雅黑; white-space: normal; letter-spacing: normal; color: #000000; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\"><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">2、</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">&nbsp;功效：降脂</span><span style=\"background-color: white; margin: 0px; font-family: Arial; color: #333333; padding: 0px;\">&nbsp;</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">通便清热解毒祛湿</span><span style=\"background-color: white; margin: 0px; font-family: Arial; color: #333333; padding: 0px;\">&nbsp;</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">、健脾开胃通便、平衡血压、调节血脂<a style=\"margin: 0px; padding: 0px;\" name=\"ref_[1]_3136353\"></a>补血补钙、滋阴壮阳</span><span style=\"background-color: white; margin: 0px; font-family: Arial; color: #333333; padding: 0px;\">&nbsp;</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\"><span class=\"Apple-converted-space\">&nbsp;</span>润泽肌肤<span class=\"Apple-converted-space\">&nbsp;</span></span><span style=\"background-color: white; margin: 0px; font-family: Arial; color: #333333; padding: 0px;\">&nbsp;</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">美容祛癍</span><span style=\"background-color: white; margin: 0px; font-family: 宋体; color: #333333; padding: 0px;\">延年益寿。</span></p>', '300', '163', null, '0', '1', 'data/files/mall/common/201411270442562544.png', '6', '460.00', '1', '0');
INSERT INTO `ecm_point_goods` VALUES ('11', '袋装巧克力牛蒡酥（100积分8袋）', '配料表：燕麦片、代可可脂、可可粉、\r\n食品添加剂：无\r\n系列: 巧克力牛蒡酥128g', '<p>100积分兑换8袋巧克力牛蒡酥</p>', '100', '284', null, '0', '1', 'data/files/mall/common/201501171626295735.jpg', null, '110.00', '0', '0');
INSERT INTO `ecm_point_goods` VALUES ('12', '牛蒡酥(100积分3盒)', '牛蒡酥在传统牛蒡酥的基础上进行大胆改进，挑选牛蒡片、蛋苕丝（糯米粉、红薯、鸡蛋）、白砂糖、麦芽糖、芝麻等营养、健康的原料精心配比秘制而成。具有牛蒡多更酥脆、更营养的特点。', '<p><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">功效：降脂</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px Arial; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">通便清热解毒祛湿</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px Arial; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">、健脾开胃通便、平衡血压、调节血脂补血补钙、滋阴壮阳</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px Arial; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">&nbsp;润泽肌肤&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px Arial; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">美容祛癍&nbsp;</span><span style=\"text-transform: none; background-color: #ffffff; text-indent: 0px; margin: 0px; font: 14px/28px 宋体; white-space: normal; letter-spacing: normal; color: #333333; word-spacing: 0px; -webkit-text-stroke-width: 0px; padding: 0px;\">延年益寿。</span></p>', '100', '351', null, '0', '1', 'data/files/mall/common/201501171630504832.jpg', null, '105.00', '0', '0');
INSERT INTO `ecm_point_goods` VALUES ('13', '礼盒牛蒡茶(100积分2盒)', '牛蒡含有一种广效的抗癌物质———牛蒡酚；还含有一种非常特殊的养分———菊糖（适合糖尿病人食用），是一种可促进性荷尔蒙分泌的精氨酸，有助于人体筋骨发达，增强体力及壮阳。', '<p><span style=\"color: #ff0000; font-size: large;\">&nbsp;牛蒡在我国以前多为药用，而在日本是家家户户日常蔬菜，含有丰富的维生素和矿物质。其胡萝卜素含量在蔬菜中居第二位；蛋白质、钙和植物纤维的含量为根类食物之首。植物纤维有助清除体内垃圾，故誉为&ldquo;大自然最佳清血剂&rdquo;；木质素是牛蒡所含最多的植物纤维的一种，具有十分突出的抗菌作用。牛蒡含有一种广效的抗癌物质&mdash;&mdash;&mdash;牛蒡酚；还含有一种非常特殊的养分&mdash;&mdash;&mdash;菊糖（适合糖尿病人食用），是一种可促进性荷尔蒙分泌的精氨酸，有助于人体筋骨发达，增强体力及壮阳。牛蒡泡茶，色泽金黄、香味宜人、价比黄金，故在台南称黄金牛蒡茶。牛蒡为我国古老的药食两用食物蔬菜，牛蒡可每日食用而无任何副作用，且对体内各系统的平衡具有复原功能。</span></p>', '100', '76', null, '0', '1', 'data/files/mall/common/201501171641475400.jpg', null, '120.00', '1', '0');
INSERT INTO `ecm_point_goods` VALUES ('14', '牛蒡菜', '能清理血液垃圾，促进体内细胞的新陈代谢，防止老化，使肌肤美丽细致，能消除色斑，黑褐斑。', '<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">&nbsp;每人每天40克。市场价60元。烧菜【荤、素均可】、烧汤、煮粥等。清水冲后进锅即可。也可分三、二次。</span></span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"letter-spacing: 1.2pt; color: #666666; font-size: 7pt;\"><strong>&nbsp;<span style=\"font-size: medium;\">牛蒡的功效</span></strong>：</span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">1、可增强人体内最硬的蛋白质&ldquo;骨胶原&rdquo;提升体内细胞活力。</span></span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">2、在体内发生化学反应。可产生三十种以上物质，其中&ldquo;多量叶酸&rdquo;能防止人体细胞发生不良的变化，防止癌细胞产生。</span></span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">3、促进体内细胞的增殖，强化和增强白血球，&ldquo;血小板&rdquo;，使T细胞以3倍的速度增长，强化免疫力，提升抗癌之功效。</span></span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">4、促进体内磷钙及维他命D在组合上之平衡，维持人体成长。</span></span></p>\r\n<p style=\"text-transform: none; text-indent: 0px; font: 12px/22px Arial, Helvetica, sans-serif; white-space: normal; letter-spacing: 2px; color: #666666; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: medium;\"><span style=\"letter-spacing: 1.2pt; color: #666666;\">5、能清理血液垃圾，促进体内细胞的新陈代谢，防止老化，使肌肤美丽细致，能消除色斑，黑褐斑。</span></span></p>', '50', '265', null, '0', '1', 'data/files/mall/common/201501171652119728.jpg', null, '60.00', '1', '0');
INSERT INTO `ecm_point_goods` VALUES ('15', '多元麦片', '', '', '45', '152', null, '0', '1', 'data/files/mall/common/201501171655194317.jpg', null, '50.00', '1', '0');

-- ----------------------------
-- Table structure for `ecm_point_goods_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_point_goods_log`;
CREATE TABLE `ecm_point_goods_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `goods_name` char(250) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` char(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` char(100) DEFAULT NULL,
  `goods_num` int(11) DEFAULT NULL,
  `total_point` int(11) DEFAULT NULL,
  `log_sn` char(100) DEFAULT NULL,
  `valid_code` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_point_goods_log
-- ----------------------------
INSERT INTO `ecm_point_goods_log` VALUES ('1', '8', 'VIP套餐（牛蒡酱8瓶）', '1471546271', 'applying', '2', 'seller', '1', '100', null, null);
INSERT INTO `ecm_point_goods_log` VALUES ('2', '9', '养元面(100积分1件9种面组成）', '1471546397', 'applying', '2', 'seller', '1', '100', null, null);
INSERT INTO `ecm_point_goods_log` VALUES ('3', '9', '养元面(100积分1件9种面组成）', '1471546458', 'passport', '2', 'seller', '1', '100', null, null);
INSERT INTO `ecm_point_goods_log` VALUES ('4', '8', 'VIP套餐（牛蒡酱8瓶）', '1471550447', 'applying', '2', 'seller', '1', '100', null, null);

-- ----------------------------
-- Table structure for `ecm_point_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_point_logs`;
CREATE TABLE `ecm_point_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `type` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_point_logs
-- ----------------------------
INSERT INTO `ecm_point_logs` VALUES ('1', '1', 'admin', '1000', '1471291196', '管理员增加积分-- 得到1000积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('2', '2', 'seller', '10000', '1471299409', '管理员增加积分-- 得到10000积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('3', '2', 'seller', '1000', '1471303460', '管理员减少积分-- 消费1000积分 ', 'system_subtract_poin');
INSERT INTO `ecm_point_logs` VALUES ('4', '2', 'seller', '1000', '1471311177', '管理员增加积分-- 得到1000积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('5', '2', 'seller', '100', '1471311427', '管理员增加积分-- 得到100积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('6', '2', 'seller', '100', '1471311577', '管理员增加积分-- 得到100积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('7', '1', 'admin', '100', '1471385059', '管理员增加积分-- 得到100积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('8', '2', 'seller', '85', '1471385105', '管理员增加积分-- 得到85积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('9', '1', 'admin', '1000', '1471385127', '管理员减少积分-- 消费1000积分 ', 'system_subtract_poin');
INSERT INTO `ecm_point_logs` VALUES ('10', '1', 'admin', '1000', '1471385150', '管理员增加积分-- 得到1000积分 ', 'system_add_point');
INSERT INTO `ecm_point_logs` VALUES ('11', '23', 'lucky', '95', '1471459401', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('12', '23', 'lucky', '95', '1471459728', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('13', '23', 'lucky', '95', '1471459823', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('14', '23', 'lucky', '95', '1471460005', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('15', '23', 'lucky', '50', '1471462433', '登陆赠送积分-- 得到50积分 ', 'login_point');
INSERT INTO `ecm_point_logs` VALUES ('16', '23', 'lucky', '567', '1471462693', '购物赠送积分-- 得到567积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('17', '23', 'lucky', '95', '1471462797', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('18', '23', 'lucky', '95', '1471462931', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('19', '23', 'lucky', '95', '1471463278', '购物赠送积分-- 得到95积分 ', 'buy_get_point');
INSERT INTO `ecm_point_logs` VALUES ('20', '2', 'seller', '50', '1471480378', '登陆赠送积分-- 得到50积分 ', 'login_point');
INSERT INTO `ecm_point_logs` VALUES ('21', '2', 'seller', '50', '1471546177', '登陆赠送积分-- 得到50积分 ', 'login_point');
INSERT INTO `ecm_point_logs` VALUES ('22', '2', 'seller', '100', '1471546271', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', 'buyer_to_point');
INSERT INTO `ecm_point_logs` VALUES ('23', '2', 'seller', '100', '1471546397', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', 'buyer_to_point');
INSERT INTO `ecm_point_logs` VALUES ('24', '2', 'seller', '100', '1471546458', '积分消费-- 消费100积分 兑换积分商品【养元面(100积分1件9种面组成）】', 'buyer_to_point');
INSERT INTO `ecm_point_logs` VALUES ('25', '2', 'seller', '100', '1471550447', '积分消费-- 消费100积分 兑换积分商品【VIP套餐（牛蒡酱8瓶）】', 'buyer_to_point');

-- ----------------------------
-- Table structure for `ecm_point_set`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_point_set`;
CREATE TABLE `ecm_point_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_point_set
-- ----------------------------
INSERT INTO `ecm_point_set` VALUES ('1', 'a:7:{s:9:\"reg_point\";s:2:\"50\";s:11:\"login_point\";s:2:\"50\";s:16:\"system_add_point\";N;s:21:\"system_subtract_point\";N;s:13:\"buy_get_point\";s:3:\"0.5\";s:17:\"recharge_to_money\";N;s:14:\"buyer_to_point\";N;}');

-- ----------------------------
-- Table structure for `ecm_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_privilege`;
CREATE TABLE `ecm_privilege` (
  `priv_code` varchar(20) NOT NULL DEFAULT '',
  `priv_name` varchar(60) NOT NULL DEFAULT '',
  `parent_code` varchar(20) DEFAULT NULL,
  `owner` varchar(10) NOT NULL DEFAULT 'mall',
  PRIMARY KEY (`priv_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_privilege
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_prize`;
CREATE TABLE `ecm_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_name` char(50) DEFAULT NULL,
  `prize_price` decimal(10,2) DEFAULT NULL,
  `prize_desc` char(250) DEFAULT NULL,
  `prize_enabled` tinyint(4) DEFAULT NULL,
  `prize_num` int(11) DEFAULT NULL,
  `prize_tags` char(250) DEFAULT NULL,
  `prize_priority` int(11) DEFAULT NULL,
  `required` float DEFAULT NULL,
  `default_image` char(250) DEFAULT NULL,
  `chance` int(11) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `wheel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_prize
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_prize_log`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_prize_log`;
CREATE TABLE `ecm_prize_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_win` tinyint(4) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `remark` char(250) DEFAULT NULL,
  `wheel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_prize_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_promotion`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_promotion`;
CREATE TABLE `ecm_promotion` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `pro_name` varchar(50) NOT NULL,
  `pro_desc` varchar(255) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `spec_price` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_promotion
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_rcategory`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_rcategory`;
CREATE TABLE `ecm_rcategory` (
  `cate_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `cate_desc` varchar(255) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `key_words` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_rcategory
-- ----------------------------
INSERT INTO `ecm_rcategory` VALUES ('1', '文字页面导航', '1', '1', '显示首页等几个分类点击分类跳转到相应分类地址', null, null);
INSERT INTO `ecm_rcategory` VALUES ('2', '首页幻灯片', '2', '1', '显示三个幻灯片，点击跳转到相应地址，幻灯片尺寸为1600*420', null, null);
INSERT INTO `ecm_rcategory` VALUES ('3', '品牌推荐', '3', '1', '显示3个滚动限时抢购（商品），10个热销品牌，7条网站公告，7条促销信息，5个滚动团购信息', '', '');
INSERT INTO `ecm_rcategory` VALUES ('4', '分类一楼', '4', '1', '显示3个滚动广告（图片大小为260*430），8个分类推荐（图片大小为225*200），其中第4和第8个会显示推荐品牌名，5个以上的推荐品牌', '1F 男女服饰', '外套 风衣 西装 女鞋');
INSERT INTO `ecm_rcategory` VALUES ('5', '分类二楼', '5', '1', '显示3个滚动广告（图片大小为260*430），6个分类推荐（其中第1个和第6个图片大小为460*200，其余的图片大小为225*200），5个以上的推荐品牌', '2F 鞋包运动', '男鞋 女鞋 跑步 低帮 男包 女包 旅行包');
INSERT INTO `ecm_rcategory` VALUES ('6', '分类三楼', '6', '1', '显示3个滚动广告（图片大小为260*430），4个分类推荐（图片大小为225*410），5个以上的推荐品牌', '3F 美容美妆', '护肤 彩妆 面霜 美体 男士护肤 面膜');
INSERT INTO `ecm_rcategory` VALUES ('7', '分类四楼', '7', '1', '显示3个滚动广告（图片大小为260*430），5个分类推荐（其中第最后两个会显示推荐品牌名，图片大小为225*200，图片大小为225*410），5个以上的推荐品牌', '4F 母婴用品', '宝宝营养 宝宝用品 早教 母亲 宝宝爱学习');
INSERT INTO `ecm_rcategory` VALUES ('8', '分类五楼', '8', '1', '显示3个滚动广告（图片大小为260*430），8个分类推荐（图片大小为225*200），其中第4和第8个会显示推荐品牌名，5个以上的推荐品牌', '5F 食品保健', '进口牛奶 正品名酒 特产 零食3折 进口零食 清肠养胃');
INSERT INTO `ecm_rcategory` VALUES ('9', '分类六楼', '9', '1', '显示3个滚动广告（图片大小为260*430），8个分类推荐，会显示分类和推荐品牌名（图片大小为220*200），5个以上的推荐品牌', '6F 数码家电', '笔记本 手机 相机 台式机 办公打印');
INSERT INTO `ecm_rcategory` VALUES ('10', '分类七楼', '10', '1', '显示3个滚动广告（图片大小为460*410），2个分类推荐，会显示分类和推荐品牌名（图片大小为220*200），5个以上的推荐品牌', '7F 家装家饰', '门 吊灯 热水器 床 沙发 四件套 羽绒被');
INSERT INTO `ecm_rcategory` VALUES ('11', '分类八楼', '11', '1', '显示3个滚动广告（图片大小为460*410），4个推荐商品，以图文展示，5个以上的推荐品牌', '8F 美容珠宝', '美白 洗护 男士 防晒 脱毛膏 珍珠');
INSERT INTO `ecm_rcategory` VALUES ('13', '全部分类', '12', '1', '显示所有商品分类', '所有商品分类', '');

-- ----------------------------
-- Table structure for `ecm_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_recommend`;
CREATE TABLE `ecm_recommend` (
  `recom_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recom_name` varchar(100) NOT NULL DEFAULT '',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`recom_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_recommend
-- ----------------------------
INSERT INTO `ecm_recommend` VALUES ('15', '裙子2', '0');
INSERT INTO `ecm_recommend` VALUES ('14', '裙子1', '0');
INSERT INTO `ecm_recommend` VALUES ('13', '外套2', '0');
INSERT INTO `ecm_recommend` VALUES ('12', '外套1', '0');
INSERT INTO `ecm_recommend` VALUES ('11', '精品', '0');
INSERT INTO `ecm_recommend` VALUES ('10', '特价2', '0');
INSERT INTO `ecm_recommend` VALUES ('9', '特价1', '0');
INSERT INTO `ecm_recommend` VALUES ('16', '美容护肤', '0');
INSERT INTO `ecm_recommend` VALUES ('17', '新品', '0');

-- ----------------------------
-- Table structure for `ecm_recommendation`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_recommendation`;
CREATE TABLE `ecm_recommendation` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `key_words` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cate_id` smallint(4) NOT NULL,
  `o_price` int(10) unsigned DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned DEFAULT '1',
  `r_type` varchar(255) NOT NULL,
  `n_price` int(10) unsigned DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `gcategory_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_recommendation
-- ----------------------------
INSERT INTO `ecm_recommendation` VALUES ('1', '幻灯片1', '', '', 'data/files/mall/recom/image/1.jpg', 'index.php', '2', '0', '1', '1', 'image_1', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('2', '幻灯片2', '', '', 'data/files/mall/recom/image/2.jpg', 'index.php?app=goods&id=23', '2', '0', '2', '1', 'image_1', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('3', '幻灯片3', '', '', 'data/files/mall/recom/image/3.jpg', 'http://www.baidu.com', '2', '0', '3', '1', 'image_1', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('4', '左边广告3', '', '', 'data/files/mall/recom/image/4.jpg', 'http://www.baidu.com', '4', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('6', '左边广告4', '', '', 'data/files/mall/recom/image/6.jpg', 'http://www.baidu.com', '5', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('7', '左广告', '', '', 'data/files/mall/recom/image/7.jpg', 'index.php?app=goods&id=6', '4', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('9', '左边广告6', '', '', 'data/files/mall/recom/image/9.jpg', '撒发松岛枫', '5', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('10', '左边广告5', '', '', 'data/files/mall/recom/image/10.jpg', '阿发松岛枫松岛枫', '5', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('11', '左边广告7', '', '', 'data/files/mall/recom/image/11.jpg', '但事实上', '6', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('12', '左边广告2', '', '', 'data/files/mall/recom/image/12.jpg', 'index.php?app=goods&id=23', '4', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('13', '阿迪达斯花式运动鞋', '', '', 'data/files/mall/recom/goods/13.jpg', 'index.php?app=goods&id=24', '3', '500', '1', '1', 'goods_1', '200', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('14', '09新款职业女裤', '', '', 'data/files/mall/recom/goods/14.jpg', 'index.php?app=goods&id=3', '3', '0', '1', '1', 'goods_2', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('15', '欧莱雅', '', '国际顶级化妆品牌', 'data/files/mall/recom/brand/15.jpg', 'http://www.baidu.com', '3', '0', '1', '1', 'brand_1', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('16', '羽绒服', '雪中飞 南极人 猫人', '精品羽绒服', 'data/files/mall/recom/recommend/16.jpg', 'http://www.baidu.com', '4', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '冬季新品', '0');
INSERT INTO `ecm_recommendation` VALUES ('17', '左边广告8', '', '', 'data/files/mall/recom/image/17.jpg', '', '6', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('18', '左边广告9', '', '', 'data/files/mall/recom/image/18.jpg', '', '6', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('19', '左边广告10', '', '', 'data/files/mall/recom/image/19.jpg', '', '7', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('20', '左边广告11', '', '', 'data/files/mall/recom/image/20.png', '', '7', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('21', '左边广告12', '', '', 'data/files/mall/recom/image/21.jpg', '', '7', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('22', '左边广告13', '', '', 'data/files/mall/recom/image/22.jpg', '', '8', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('23', '左边广告14', '', '', 'data/files/mall/recom/image/23.jpg', '', '8', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('24', '左边广告15', '', '', 'data/files/mall/recom/image/24.png', '', '8', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('25', '左边广告16', '', '', 'data/files/mall/recom/image/25.jpg', '', '9', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('26', '左边广告17', '', '', 'data/files/mall/recom/image/26.jpg', '', '9', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('27', '左边广告18', '', '', 'data/files/mall/recom/image/27.jpg', '', '9', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('28', '左边广告19', '', '', 'data/files/mall/recom/image/28.jpg', '', '10', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('29', '左边广告20', '', '', 'data/files/mall/recom/image/29.png', '', '10', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('30', '左边广告21', '', '', 'data/files/mall/recom/image/30.jpg', '', '10', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('31', '左边广告22', '', '', 'data/files/mall/recom/image/31.jpg', '', '11', '0', '1', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('32', '左边广告23', '', '', 'data/files/mall/recom/image/32.jpg', '', '11', '0', '2', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('33', '左边广告24', '', '', 'data/files/mall/recom/image/33.jpg', '', '11', '0', '3', '1', 'image_2', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('34', '品牌下部广告', '', '', 'data/files/mall/recom/image/34.jpg', '', '3', '0', '1', '1', 'image_3', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('35', '多彩人生多彩裤', '', '多彩人生多彩裤', 'data/files/mall/recom/goods/35.jpg', 'index.php?app=goods&id=1', '3', '0', '2', '1', 'goods_2', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('36', '花色高邦运动鞋', '', '花色高邦运动鞋', 'data/files/mall/recom/goods/36.jpg', 'index.php?app=search&brand=', '3', '0', '3', '1', 'goods_2', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('37', 'ESprit', '', '多彩人生多彩裤', 'data/files/mall/recom/brand/37.jpg', 'index.php?app=search&brand=ESprit', '3', '0', '2', '1', 'brand_1', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('38', '李宁', '', '一切皆有可能', 'data/files/mall/recom/brand/38.jpg', '', '3', '0', '3', '1', 'brand_2', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('39', 'G-Star', '', '花色高邦运动鞋', 'data/files/mall/recom/brand/39.jpg', 'index.php?app=search&brand=G-Star', '3', '0', '4', '1', 'brand_2', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('40', 'Lee', '', '中国沙发第一品牌', 'data/files/mall/recom/brand/40.jpg', '', '3', '0', '5', '1', 'brand_2', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('41', 'Jack&Jones', '', '纯正独特忠于原创', 'data/files/mall/recom/brand/41.jpg', 'index.php?app=search&brand=Jack+%26+Jones', '3', '0', '6', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('42', '麦包包', '', '', 'data/files/mall/recom/brand/42.gif', 'index.php?app=search&brand=%E9%BA%A6%E5%8C%85%E5%8C%85', '4', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('43', 'ESprit', '', '', 'data/files/mall/recom/brand/43.jpg', 'index.php?app=search&brand=ESprit', '5', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('44', '一切皆有可能', '', '', 'data/files/mall/recom/brand/44.jpg', 'index.php?app=search&brand=%E6%9D%8E%E5%AE%81', '6', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('45', 'G-Star', '', '', 'data/files/mall/recom/brand/45.jpg', 'index.php?app=search&brand=G-Star', '7', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('46', 'Lee', '', '', 'data/files/mall/recom/brand/46.jpg', '', '8', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('47', 'Jack&Jones', '', '', 'data/files/mall/recom/brand/47.jpg', '', '9', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('48', 'DIOR', '', '', 'data/files/mall/recom/brand/48.jpg', 'http://localhost:8004/index.php?app=search&brand=DIOR', '10', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('49', 'Chanel', '', '', 'data/files/mall/recom/brand/49.jpg', '', '11', '0', '1', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('50', '时尚女裤', '', '冬季新品', 'data/files/mall/recom/recommend/50.jpg', 'http://localhost:8004/index.php?app=goods&id=17', '4', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('51', '睡衣', '', '新品睡衣', 'data/files/mall/recom/recommend/51.jpg', 'http://localhost:8004/index.php?app=goods&id=17', '4', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('52', '时尚男装', '', '男士衬衣', 'data/files/mall/recom/recommend/52.jpg', '', '4', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('53', '男士西服', '', '新品抢购', 'data/files/mall/recom/recommend/53.jpg', '', '4', '0', '5', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('54', '男士休闲', '', '新品抢购', 'data/files/mall/recom/recommend/54.jpg', '', '4', '0', '6', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('55', '男士羽绒服', '', '冬季新装', 'data/files/mall/recom/recommend/55.jpg', '', '4', '0', '7', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('56', '新品', '雪中飞 猫人', 'ESprit', 'data/files/mall/recom/recommend/56.png', '', '4', '0', '8', '1', 'recommend_1', '0', '', 'recommend', '冬季新装', '0');
INSERT INTO `ecm_recommendation` VALUES ('57', '冬季新品', '', '羽绒服抢购', 'data/files/mall/recom/recommend/57.jpg', '', '5', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('58', '夏鞋', '', '新品', 'data/files/mall/recom/recommend/58.jpg', '', '5', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('59', '时尚品牌秀', '', '以纯清仓大处理', 'data/files/mall/recom/recommend/59.jpg', '', '5', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('60', '夏季爆款', '', '女士短袖精品', 'data/files/mall/recom/recommend/60.jpg', '', '5', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('61', '感恩party', '', '家具睡衣全场包邮', 'data/files/mall/recom/recommend/61.jpg', '', '5', '0', '5', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('62', '冬季新品', '', '新包抢购', 'data/files/mall/recom/recommend/62.jpg', '', '5', '0', '6', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('63', '洗发', '', 'VS 新品抢购', 'data/files/mall/recom/recommend/63.jpg', '', '6', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('64', '国际大牌香水管', '', '新品抢购', 'data/files/mall/recom/recommend/64.jpg', '', '6', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('65', '香聚', '', '国际香水管', 'data/files/mall/recom/recommend/65.jpg', '', '6', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('66', '美肤宝', '', '东方之美', 'data/files/mall/recom/recommend/66.jpg', '', '6', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('67', '可爱宝贝', '', '宝宝睡衣', 'data/files/mall/recom/recommend/67.jpg', '', '7', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('68', '童装', '', '童装男', 'data/files/mall/recom/recommend/68.jpg', '', '7', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('69', '奶粉', '', '婴儿奶粉', 'data/files/mall/recom/recommend/69.jpg', '', '7', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('70', '奶粉', '惠氏 美仑佳雅培', '惠氏', 'data/files/mall/recom/recommend/70.jpg', '', '7', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '进口奶粉排行榜', '0');
INSERT INTO `ecm_recommendation` VALUES ('71', '奶粉2', '美赞成 雀巢 多美滋', '美仑加', 'data/files/mall/recom/recommend/71.jpg', '', '7', '0', '5', '1', 'recommend_1', '0', '', 'recommend', '品牌奶粉', '0');
INSERT INTO `ecm_recommendation` VALUES ('72', '藏药大补', '', '冬虫夏草干', 'data/files/mall/recom/recommend/72.jpg', '', '8', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('73', '感恩party', '', '快速减肥左旋360', 'data/files/mall/recom/recommend/73.jpg', '', '8', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('74', '端午又一年', '', '2015新粽上市', 'data/files/mall/recom/recommend/74.jpg', '', '8', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('75', '钙尔奇', '优乐美 喜之郎 ESprit', '钙尔奇牛奶', 'data/files/mall/recom/recommend/75.jpg', '', '8', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '饮品', '0');
INSERT INTO `ecm_recommendation` VALUES ('76', '端午节', '', '陈年黄酒格外香', 'data/files/mall/recom/recommend/76.jpg', '', '8', '0', '5', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('77', '浓情咖啡', '', '全新卡布奇诺口味黑咖啡', 'data/files/mall/recom/recommend/77.jpg', '', '8', '0', '6', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('78', '主食展', '', '风靡世界的意大利面', 'data/files/mall/recom/recommend/78.jpg', 'http://www.baidu.com', '8', '0', '7', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('79', '调料', '老干妈 PUMA', '浦沅虾酱', 'data/files/mall/recom/recommend/79.jpg', '', '8', '0', '8', '1', 'recommend_1', '0', '', 'recommend', '调味品', '0');
INSERT INTO `ecm_recommendation` VALUES ('80', '平板', '苹果 小米 三星', '', 'data/files/mall/recom/recommend/80.jpg', '', '9', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '手机', '0');
INSERT INTO `ecm_recommendation` VALUES ('81', '笔记本', '联想 苹果 惠普', '', 'data/files/mall/recom/recommend/81.jpg', '', '9', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '笔记本', '0');
INSERT INTO `ecm_recommendation` VALUES ('82', '相机', '尼康 三星 卡西欧', '', 'data/files/mall/recom/recommend/82.jpg', '', '9', '0', '3', '1', 'recommend_1', '0', '', 'recommend', '相机', '0');
INSERT INTO `ecm_recommendation` VALUES ('83', '台式机', '联想 苹果 戴尔', '', 'data/files/mall/recom/recommend/83.jpg', '', '9', '0', '4', '1', 'recommend_1', '0', '', 'recommend', '台式机', '0');
INSERT INTO `ecm_recommendation` VALUES ('84', '手机', '苹果 三星 小米', '', 'data/files/mall/recom/recommend/84.jpg', 'http://localhost:8004/index.php?app=goods&id=36', '9', '0', '5', '1', 'recommend_1', '0', '', 'recommend', '手机', '0');
INSERT INTO `ecm_recommendation` VALUES ('85', '电脑硬件', '尼康 三星 卡西欧', '', 'data/files/mall/recom/recommend/85.jpg', '', '9', '0', '6', '1', 'recommend_1', '0', '', 'recommend', '电脑硬件', '0');
INSERT INTO `ecm_recommendation` VALUES ('86', '外设', '金士顿 闪迪 东芝', '', 'data/files/mall/recom/recommend/86.jpg', '', '9', '0', '7', '1', 'recommend_1', '0', '', 'recommend', 'U盘', '0');
INSERT INTO `ecm_recommendation` VALUES ('87', '办公打印', '富士施乐 京东', '', 'data/files/mall/recom/recommend/87.jpg', '', '9', '0', '8', '1', 'recommend_1', '0', '', 'recommend', '打印机', '0');
INSERT INTO `ecm_recommendation` VALUES ('88', '家装材料', '', '家装材料任您挑选', 'data/files/mall/recom/recommend/88.jpg', 'http://localhost:8004/index.php?app=goods&id=37', '10', '0', '1', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('89', '家装饰品', '', '经典意境家装饰品', 'data/files/mall/recom/recommend/89.jpg', 'http://localhost:8004/index.php?app=goods&id=37', '10', '0', '2', '1', 'recommend_1', '0', '', 'recommend', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('90', '珂兰钻石 钻戒GIA裸钻定制 30分-1克拉求婚结婚对戒指专柜正品QH', '', '总销量：10000 | 累计评价：5500', 'data/files/mall/recom/goods/90.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1243', '11', '8000', '1', '1', 'goods_3', '3190', '珂兰钻石旗舰店', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('91', '天然正品翡翠a货墨翠吊坠透光观音佛龙貔貅钟馗玉器挂件批发包证', '', '总销量：10000 | 累计评价：5500', 'data/files/mall/recom/goods/91.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1243', '11', '1000', '2', '1', 'goods_3', '200', '千珲珠宝', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('92', '翡翠冰玻种佛公复鉴后再付款带国家证书熙韵xy10921白色玉器吊坠', '', '总销量：1000 | 累计评价：500', 'data/files/mall/recom/goods/92.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1243', '11', '1300', '3', '1', 'goods_3', '300', '熙韵翡翠', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('93', '真正18K黄金项链/玫瑰金/白金 加粗空肖邦链 男式女士K金长毛衣链', '', '零利润促销', 'data/files/mall/recom/goods/93.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1243', '11', '2200', '4', '1', 'goods_3', '1000', '维恩珠宝', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('94', '香港代购韩国SZ2014冬装中长款羽绒服休闲气质女装加厚时尚女外套', '', '', '', 'http://localhost:8004/index.php?app=goods&id=33', '3', '0', '1', '1', 'goods_4', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('95', '2014秋冬新款斜挎女士包包手提包时尚单肩包子母女包大包大容量潮', '', '', '', 'http://localhost:8004/index.php?app=goods&id=32', '3', '0', '2', '1', 'goods_4', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('96', '2014冬季新款雪地靴厚底真皮兔毛中筒靴平底防滑棉靴女鞋特价清仓', '', '', '', 'http://localhost:8004/index.php?app=goods&id=31', '3', '0', '3', '1', 'goods_4', '0', '', 'goods', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('97', '首页', '', '', '', 'index.php', '1', '0', '1', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('98', '服装服饰', '', '', '', 'index.php?app=search&cate_id=21', '1', '0', '2', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('99', '数码家电', '', '', '', 'index.php?app=search&cate_id=1242', '1', '0', '3', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('100', '箱包配饰', '', '', '', 'index.php?app=search&cate_id=206', '1', '0', '4', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('101', '母婴用品', '', '', '', 'index.php?app=search&cate_id=1240', '1', '0', '5', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('102', '品牌', '', '', '', 'index.php?app=brand', '1', '0', '6', '1', 'image_4', '0', '', 'image', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('103', '男装', '', '', 'data/files/mall/recom/image/103.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1', '13', '0', '1', '1', 'image_5', '0', '', 'image', '', '1');
INSERT INTO `ecm_recommendation` VALUES ('104', '男装2', '', '', 'data/files/mall/recom/image/104.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1', '13', '0', '2', '1', 'image_5', '0', '', 'image', '', '1');
INSERT INTO `ecm_recommendation` VALUES ('105', '男鞋', '', '', 'data/files/mall/recom/image/105.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1', '13', '0', '3', '1', 'image_5', '0', '', 'image', '', '162');
INSERT INTO `ecm_recommendation` VALUES ('106', '女鞋', '', '', 'data/files/mall/recom/image/106.jpg', 'http://localhost:8004/index.php?app=search&cate_id=172', '13', '0', '1', '1', 'image_5', '0', '', 'image', '', '172');
INSERT INTO `ecm_recommendation` VALUES ('107', '手机数码', '', '', 'data/files/mall/recom/image/107.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1242', '13', '0', '1', '1', 'image_5', '0', '', 'image', '', '1242');
INSERT INTO `ecm_recommendation` VALUES ('108', '手机数码2', '', '', 'data/files/mall/recom/image/108.jpg', 'http://localhost:8004/index.php?app=search&cate_id=1242', '13', '0', '2', '1', 'image_5', '0', '', 'image', '', '1242');
INSERT INTO `ecm_recommendation` VALUES ('109', '手机数码', '', '', 'data/files/mall/recom/image/109.jpg', '', '13', '0', '2', '1', 'image_5', '0', '', 'image', '', '1242');
INSERT INTO `ecm_recommendation` VALUES ('110', '母婴', '', '', 'data/files/mall/recom/image/110.jpg', '', '13', '0', '1', '1', 'image_5', '0', '', 'image', '', '1240');
INSERT INTO `ecm_recommendation` VALUES ('111', '男鞋3', '', '', 'data/files/mall/recom/image/111.jpg', '', '13', '0', '3', '1', 'image_5', '0', '', 'image', '', '162');
INSERT INTO `ecm_recommendation` VALUES ('112', '男装3', '', '', 'data/files/mall/recom/image/112.jpg', '', '13', '0', '3', '1', 'image_5', '0', '', 'image', '', '1');
INSERT INTO `ecm_recommendation` VALUES ('113', '阿迪王', '', '', 'data/files/mall/recom/brand/113.jpg', '', '4', '0', '2', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('114', '李宁', '', '', 'data/files/mall/recom/brand/114.jpg', '', '4', '0', '3', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('115', 'AMINTA', '', '', 'data/files/mall/recom/brand/115.jpg', '', '4', '0', '4', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('116', 'JAC', '', '', 'data/files/mall/recom/brand/116.jpg', '', '4', '0', '5', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('117', '艾莜', '', '', 'data/files/mall/recom/brand/117.jpg', '', '4', '0', '6', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('118', '容悦', '', '', 'data/files/mall/recom/brand/118.jpg', '', '4', '0', '6', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('119', '喜梦宝', '', '纯正独特忠于原创', 'data/files/mall/recom/brand/119.gif', '', '3', '0', '7', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('120', '联想', '', '纯正独特忠于原创', 'data/files/mall/recom/brand/120.jpg', '', '3', '0', '8', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('121', '美邦', '', '纯正独特忠于原创', 'data/files/mall/recom/brand/121.jpg', '', '3', '0', '9', '1', 'brand_3', '0', '', 'brand', '', '0');
INSERT INTO `ecm_recommendation` VALUES ('122', '欧普', '', '欧普照明', 'data/files/mall/recom/brand/122.jpg', '', '3', '0', '10', '1', 'brand_3', '0', '', 'brand', '', '0');

-- ----------------------------
-- Table structure for `ecm_recommended_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_recommended_goods`;
CREATE TABLE `ecm_recommended_goods` (
  `recom_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`recom_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_recommended_goods
-- ----------------------------
INSERT INTO `ecm_recommended_goods` VALUES ('15', '17', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '18', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '19', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '20', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '21', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '22', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '23', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '24', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '25', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '26', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('15', '27', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '28', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '29', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '1', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '2', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '3', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '4', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '5', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '6', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '7', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '8', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '9', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '10', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '11', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('13', '12', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('12', '13', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('12', '14', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('12', '15', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('12', '16', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '17', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '18', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '19', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '20', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '21', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('11', '22', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('10', '23', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('10', '24', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('10', '25', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('10', '26', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('9', '27', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('9', '28', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('9', '29', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('9', '22', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('9', '21', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '76', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '75', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '74', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '73', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '71', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '70', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('14', '69', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('16', '8', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('16', '5', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('16', '4', '255');
INSERT INTO `ecm_recommended_goods` VALUES ('17', '29', '255');

-- ----------------------------
-- Table structure for `ecm_refund`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_refund`;
CREATE TABLE `ecm_refund` (
  `refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_sn` varchar(50) NOT NULL,
  `order_id` int(10) NOT NULL,
  `refund_reason` varchar(50) NOT NULL,
  `refund_desc` varchar(255) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `goods_fee` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `refund_goods_fee` decimal(10,2) NOT NULL,
  `refund_shipping_fee` decimal(10,2) NOT NULL,
  `buyer_id` int(10) NOT NULL,
  `seller_id` int(10) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT '',
  `shipped` int(11) NOT NULL,
  `ask_customer` int(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`refund_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_refund
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_refund_message`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_refund_message`;
CREATE TABLE `ecm_refund_message` (
  `rm_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `owner_role` varchar(10) NOT NULL,
  `refund_id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `pic_url` varchar(255) DEFAULT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`rm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_refund_message
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_region`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_region`;
CREATE TABLE `ecm_region` (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=477 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_region
-- ----------------------------
INSERT INTO `ecm_region` VALUES ('2', '中国', '0', '255');
INSERT INTO `ecm_region` VALUES ('3', '北京市', '2', '255');
INSERT INTO `ecm_region` VALUES ('4', '东城', '3', '255');
INSERT INTO `ecm_region` VALUES ('5', '西城', '3', '255');
INSERT INTO `ecm_region` VALUES ('6', '崇文', '3', '255');
INSERT INTO `ecm_region` VALUES ('7', '宣武', '3', '255');
INSERT INTO `ecm_region` VALUES ('8', '朝阳', '3', '255');
INSERT INTO `ecm_region` VALUES ('9', '海淀', '3', '255');
INSERT INTO `ecm_region` VALUES ('10', '丰台', '3', '255');
INSERT INTO `ecm_region` VALUES ('11', '石景山', '3', '255');
INSERT INTO `ecm_region` VALUES ('12', '门头沟', '3', '255');
INSERT INTO `ecm_region` VALUES ('13', '房山', '3', '255');
INSERT INTO `ecm_region` VALUES ('14', '通州', '3', '255');
INSERT INTO `ecm_region` VALUES ('15', '顺义', '3', '255');
INSERT INTO `ecm_region` VALUES ('16', '大兴', '3', '255');
INSERT INTO `ecm_region` VALUES ('17', '昌平', '3', '255');
INSERT INTO `ecm_region` VALUES ('18', '平谷', '3', '255');
INSERT INTO `ecm_region` VALUES ('19', '怀柔', '3', '255');
INSERT INTO `ecm_region` VALUES ('20', '延庆', '3', '255');
INSERT INTO `ecm_region` VALUES ('21', '密云', '3', '255');
INSERT INTO `ecm_region` VALUES ('22', '天津市', '2', '255');
INSERT INTO `ecm_region` VALUES ('23', '和平区', '22', '255');
INSERT INTO `ecm_region` VALUES ('24', '河东区', '22', '255');
INSERT INTO `ecm_region` VALUES ('25', '河西区', '22', '255');
INSERT INTO `ecm_region` VALUES ('26', '南开区', '22', '255');
INSERT INTO `ecm_region` VALUES ('27', '河北区', '22', '255');
INSERT INTO `ecm_region` VALUES ('28', '红桥区', '22', '255');
INSERT INTO `ecm_region` VALUES ('29', '塘沽区', '22', '255');
INSERT INTO `ecm_region` VALUES ('30', '汉沽区', '22', '255');
INSERT INTO `ecm_region` VALUES ('31', '大港区', '22', '255');
INSERT INTO `ecm_region` VALUES ('32', '西青区', '22', '255');
INSERT INTO `ecm_region` VALUES ('33', '东丽区', '22', '255');
INSERT INTO `ecm_region` VALUES ('34', '津南区', '22', '255');
INSERT INTO `ecm_region` VALUES ('35', '北辰区', '22', '255');
INSERT INTO `ecm_region` VALUES ('36', '武清区', '22', '255');
INSERT INTO `ecm_region` VALUES ('37', '宝坻区', '22', '255');
INSERT INTO `ecm_region` VALUES ('38', '静海县', '22', '255');
INSERT INTO `ecm_region` VALUES ('39', '宁河县', '22', '255');
INSERT INTO `ecm_region` VALUES ('40', '蓟县', '22', '255');
INSERT INTO `ecm_region` VALUES ('41', '上海市', '2', '255');
INSERT INTO `ecm_region` VALUES ('42', '浦东新区', '41', '255');
INSERT INTO `ecm_region` VALUES ('43', '徐汇区', '41', '255');
INSERT INTO `ecm_region` VALUES ('44', '长宁区', '41', '255');
INSERT INTO `ecm_region` VALUES ('45', '普陀区', '41', '255');
INSERT INTO `ecm_region` VALUES ('46', '闸北区', '41', '255');
INSERT INTO `ecm_region` VALUES ('47', '虹口区', '41', '255');
INSERT INTO `ecm_region` VALUES ('48', '杨浦区', '41', '255');
INSERT INTO `ecm_region` VALUES ('49', '黄浦区', '41', '255');
INSERT INTO `ecm_region` VALUES ('50', '卢湾区', '41', '255');
INSERT INTO `ecm_region` VALUES ('51', '静安区', '41', '255');
INSERT INTO `ecm_region` VALUES ('52', '宝山区', '41', '255');
INSERT INTO `ecm_region` VALUES ('53', '闵行区', '41', '255');
INSERT INTO `ecm_region` VALUES ('54', '嘉定区', '41', '255');
INSERT INTO `ecm_region` VALUES ('55', '金山区', '41', '255');
INSERT INTO `ecm_region` VALUES ('56', '松江区', '41', '255');
INSERT INTO `ecm_region` VALUES ('57', '青浦区', '41', '255');
INSERT INTO `ecm_region` VALUES ('58', '崇明县', '41', '255');
INSERT INTO `ecm_region` VALUES ('59', '奉贤区', '41', '255');
INSERT INTO `ecm_region` VALUES ('60', '南汇区', '41', '255');
INSERT INTO `ecm_region` VALUES ('61', '重庆市', '2', '255');
INSERT INTO `ecm_region` VALUES ('62', '渝中', '61', '255');
INSERT INTO `ecm_region` VALUES ('63', '大渡口', '61', '255');
INSERT INTO `ecm_region` VALUES ('64', '江北', '61', '255');
INSERT INTO `ecm_region` VALUES ('65', '沙坪坝', '61', '255');
INSERT INTO `ecm_region` VALUES ('66', '九龙坡', '61', '255');
INSERT INTO `ecm_region` VALUES ('67', '南岸', '61', '255');
INSERT INTO `ecm_region` VALUES ('68', '北碚', '61', '255');
INSERT INTO `ecm_region` VALUES ('69', '渝北', '61', '255');
INSERT INTO `ecm_region` VALUES ('70', '巴南', '61', '255');
INSERT INTO `ecm_region` VALUES ('71', '北部新区', '61', '255');
INSERT INTO `ecm_region` VALUES ('72', '经开区', '61', '255');
INSERT INTO `ecm_region` VALUES ('73', '万盛', '61', '255');
INSERT INTO `ecm_region` VALUES ('74', '双桥', '61', '255');
INSERT INTO `ecm_region` VALUES ('75', '綦江', '61', '255');
INSERT INTO `ecm_region` VALUES ('76', '潼南', '61', '255');
INSERT INTO `ecm_region` VALUES ('77', '铜梁', '61', '255');
INSERT INTO `ecm_region` VALUES ('78', '大足', '61', '255');
INSERT INTO `ecm_region` VALUES ('79', '荣昌', '61', '255');
INSERT INTO `ecm_region` VALUES ('80', '璧山', '61', '255');
INSERT INTO `ecm_region` VALUES ('81', '江津', '61', '255');
INSERT INTO `ecm_region` VALUES ('82', '合川', '61', '255');
INSERT INTO `ecm_region` VALUES ('83', '永川', '61', '255');
INSERT INTO `ecm_region` VALUES ('84', '南川', '61', '255');
INSERT INTO `ecm_region` VALUES ('85', '万州', '61', '255');
INSERT INTO `ecm_region` VALUES ('86', '涪陵', '61', '255');
INSERT INTO `ecm_region` VALUES ('87', '黔江', '61', '255');
INSERT INTO `ecm_region` VALUES ('88', '长寿', '61', '255');
INSERT INTO `ecm_region` VALUES ('89', '梁平', '61', '255');
INSERT INTO `ecm_region` VALUES ('90', '城口', '61', '255');
INSERT INTO `ecm_region` VALUES ('91', '丰都', '61', '255');
INSERT INTO `ecm_region` VALUES ('92', '垫江', '61', '255');
INSERT INTO `ecm_region` VALUES ('93', '武隆', '61', '255');
INSERT INTO `ecm_region` VALUES ('94', '忠县', '61', '255');
INSERT INTO `ecm_region` VALUES ('95', '开县', '61', '255');
INSERT INTO `ecm_region` VALUES ('96', '云阳', '61', '255');
INSERT INTO `ecm_region` VALUES ('97', '奉节', '61', '255');
INSERT INTO `ecm_region` VALUES ('98', '巫山', '61', '255');
INSERT INTO `ecm_region` VALUES ('99', '巫溪', '61', '255');
INSERT INTO `ecm_region` VALUES ('100', '石柱', '61', '255');
INSERT INTO `ecm_region` VALUES ('101', '秀山', '61', '255');
INSERT INTO `ecm_region` VALUES ('102', '酉阳', '61', '255');
INSERT INTO `ecm_region` VALUES ('103', '彭水', '61', '255');
INSERT INTO `ecm_region` VALUES ('104', '河北省', '2', '255');
INSERT INTO `ecm_region` VALUES ('105', '石家庄', '104', '255');
INSERT INTO `ecm_region` VALUES ('106', '衡水', '104', '255');
INSERT INTO `ecm_region` VALUES ('107', '唐山', '104', '255');
INSERT INTO `ecm_region` VALUES ('108', '秦皇岛', '104', '255');
INSERT INTO `ecm_region` VALUES ('109', '张家口', '104', '255');
INSERT INTO `ecm_region` VALUES ('110', '承德', '104', '255');
INSERT INTO `ecm_region` VALUES ('111', '邯郸', '104', '255');
INSERT INTO `ecm_region` VALUES ('112', '沧州', '104', '255');
INSERT INTO `ecm_region` VALUES ('113', '邢台', '104', '255');
INSERT INTO `ecm_region` VALUES ('114', '保定', '104', '255');
INSERT INTO `ecm_region` VALUES ('115', '廊坊', '104', '255');
INSERT INTO `ecm_region` VALUES ('116', '山西省', '2', '255');
INSERT INTO `ecm_region` VALUES ('117', '太原市', '116', '255');
INSERT INTO `ecm_region` VALUES ('118', '大同市', '116', '255');
INSERT INTO `ecm_region` VALUES ('119', '朔州市', '116', '255');
INSERT INTO `ecm_region` VALUES ('120', '忻州市', '116', '255');
INSERT INTO `ecm_region` VALUES ('121', '长治市', '116', '255');
INSERT INTO `ecm_region` VALUES ('122', '阳泉市', '116', '255');
INSERT INTO `ecm_region` VALUES ('123', '晋中市', '116', '255');
INSERT INTO `ecm_region` VALUES ('124', '吕梁市', '116', '255');
INSERT INTO `ecm_region` VALUES ('125', '晋城市', '116', '255');
INSERT INTO `ecm_region` VALUES ('126', '临汾市', '116', '255');
INSERT INTO `ecm_region` VALUES ('127', '运城市', '116', '255');
INSERT INTO `ecm_region` VALUES ('128', '辽宁省', '2', '255');
INSERT INTO `ecm_region` VALUES ('129', '沈阳', '128', '255');
INSERT INTO `ecm_region` VALUES ('130', '大连', '128', '255');
INSERT INTO `ecm_region` VALUES ('131', '鞍山', '128', '255');
INSERT INTO `ecm_region` VALUES ('132', '抚顺', '128', '255');
INSERT INTO `ecm_region` VALUES ('133', '本溪', '128', '255');
INSERT INTO `ecm_region` VALUES ('134', '丹东', '128', '255');
INSERT INTO `ecm_region` VALUES ('135', '锦州', '128', '255');
INSERT INTO `ecm_region` VALUES ('136', '营口', '128', '255');
INSERT INTO `ecm_region` VALUES ('137', '阜新', '128', '255');
INSERT INTO `ecm_region` VALUES ('138', '辽阳', '128', '255');
INSERT INTO `ecm_region` VALUES ('139', '铁岭', '128', '255');
INSERT INTO `ecm_region` VALUES ('140', '朝阳', '128', '255');
INSERT INTO `ecm_region` VALUES ('141', '盘锦', '128', '255');
INSERT INTO `ecm_region` VALUES ('142', '葫芦岛', '128', '255');
INSERT INTO `ecm_region` VALUES ('143', '吉林省', '2', '255');
INSERT INTO `ecm_region` VALUES ('144', '长春市', '143', '255');
INSERT INTO `ecm_region` VALUES ('145', '吉林市', '143', '255');
INSERT INTO `ecm_region` VALUES ('146', '四平市', '143', '255');
INSERT INTO `ecm_region` VALUES ('147', '辽源市', '143', '255');
INSERT INTO `ecm_region` VALUES ('148', '通化市', '143', '255');
INSERT INTO `ecm_region` VALUES ('149', '白山市', '143', '255');
INSERT INTO `ecm_region` VALUES ('150', '松原市', '143', '255');
INSERT INTO `ecm_region` VALUES ('151', '白城市', '143', '255');
INSERT INTO `ecm_region` VALUES ('152', '延边州', '143', '255');
INSERT INTO `ecm_region` VALUES ('153', '黑龙江省', '2', '255');
INSERT INTO `ecm_region` VALUES ('154', '哈尔滨', '153', '255');
INSERT INTO `ecm_region` VALUES ('155', '齐齐哈尔', '153', '255');
INSERT INTO `ecm_region` VALUES ('156', '牡丹江', '153', '255');
INSERT INTO `ecm_region` VALUES ('157', '佳木斯', '153', '255');
INSERT INTO `ecm_region` VALUES ('158', '大庆', '153', '255');
INSERT INTO `ecm_region` VALUES ('159', '鸡西', '153', '255');
INSERT INTO `ecm_region` VALUES ('160', '伊春', '153', '255');
INSERT INTO `ecm_region` VALUES ('161', '双鸭山', '153', '255');
INSERT INTO `ecm_region` VALUES ('162', '七台河', '153', '255');
INSERT INTO `ecm_region` VALUES ('163', '鹤岗', '153', '255');
INSERT INTO `ecm_region` VALUES ('164', '黑河', '153', '255');
INSERT INTO `ecm_region` VALUES ('165', '绥化', '153', '255');
INSERT INTO `ecm_region` VALUES ('166', '大兴安岭', '153', '255');
INSERT INTO `ecm_region` VALUES ('167', '内蒙古自治区', '2', '255');
INSERT INTO `ecm_region` VALUES ('168', '呼和浩特市', '167', '255');
INSERT INTO `ecm_region` VALUES ('169', '包头市', '167', '255');
INSERT INTO `ecm_region` VALUES ('170', '乌海市', '167', '255');
INSERT INTO `ecm_region` VALUES ('171', '赤峰市', '167', '255');
INSERT INTO `ecm_region` VALUES ('172', '通辽市', '167', '255');
INSERT INTO `ecm_region` VALUES ('173', '鄂尔多斯市', '167', '255');
INSERT INTO `ecm_region` VALUES ('174', '呼伦贝尔市', '167', '255');
INSERT INTO `ecm_region` VALUES ('175', '巴彦淖尔市', '167', '255');
INSERT INTO `ecm_region` VALUES ('176', '乌兰察布市', '167', '255');
INSERT INTO `ecm_region` VALUES ('177', '锡林郭勒盟', '167', '255');
INSERT INTO `ecm_region` VALUES ('178', '兴安盟', '167', '255');
INSERT INTO `ecm_region` VALUES ('179', '阿拉善盟', '167', '255');
INSERT INTO `ecm_region` VALUES ('180', '江苏省', '2', '255');
INSERT INTO `ecm_region` VALUES ('181', '南京', '180', '255');
INSERT INTO `ecm_region` VALUES ('182', '苏州', '180', '255');
INSERT INTO `ecm_region` VALUES ('183', '无锡', '180', '255');
INSERT INTO `ecm_region` VALUES ('184', '常州', '180', '255');
INSERT INTO `ecm_region` VALUES ('185', '扬州', '180', '255');
INSERT INTO `ecm_region` VALUES ('186', '南通', '180', '255');
INSERT INTO `ecm_region` VALUES ('187', '镇江', '180', '255');
INSERT INTO `ecm_region` VALUES ('188', '泰州', '180', '255');
INSERT INTO `ecm_region` VALUES ('189', '淮安', '180', '255');
INSERT INTO `ecm_region` VALUES ('190', '徐州', '180', '255');
INSERT INTO `ecm_region` VALUES ('191', '盐城', '180', '255');
INSERT INTO `ecm_region` VALUES ('192', '宿迁', '180', '255');
INSERT INTO `ecm_region` VALUES ('193', '连云港', '180', '255');
INSERT INTO `ecm_region` VALUES ('194', '浙江省', '2', '255');
INSERT INTO `ecm_region` VALUES ('195', '杭州', '194', '255');
INSERT INTO `ecm_region` VALUES ('196', '宁波', '194', '255');
INSERT INTO `ecm_region` VALUES ('197', '温州', '194', '255');
INSERT INTO `ecm_region` VALUES ('198', '嘉兴', '194', '255');
INSERT INTO `ecm_region` VALUES ('199', '湖州', '194', '255');
INSERT INTO `ecm_region` VALUES ('200', '绍兴', '194', '255');
INSERT INTO `ecm_region` VALUES ('201', '金华', '194', '255');
INSERT INTO `ecm_region` VALUES ('202', '衢州', '194', '255');
INSERT INTO `ecm_region` VALUES ('203', '舟山', '194', '255');
INSERT INTO `ecm_region` VALUES ('204', '台州', '194', '255');
INSERT INTO `ecm_region` VALUES ('205', '丽水', '194', '255');
INSERT INTO `ecm_region` VALUES ('206', '安徽省', '2', '255');
INSERT INTO `ecm_region` VALUES ('207', '淮北市', '206', '255');
INSERT INTO `ecm_region` VALUES ('208', '合肥市', '206', '255');
INSERT INTO `ecm_region` VALUES ('209', '六安市', '206', '255');
INSERT INTO `ecm_region` VALUES ('210', '亳州市', '206', '255');
INSERT INTO `ecm_region` VALUES ('211', '宿州市', '206', '255');
INSERT INTO `ecm_region` VALUES ('212', '阜阳市', '206', '255');
INSERT INTO `ecm_region` VALUES ('213', '蚌埠市', '206', '255');
INSERT INTO `ecm_region` VALUES ('214', '淮南市', '206', '255');
INSERT INTO `ecm_region` VALUES ('215', '滁州市', '206', '255');
INSERT INTO `ecm_region` VALUES ('216', '巢湖市', '206', '255');
INSERT INTO `ecm_region` VALUES ('217', '芜湖市', '206', '255');
INSERT INTO `ecm_region` VALUES ('218', '马鞍山', '206', '255');
INSERT INTO `ecm_region` VALUES ('219', '安庆市', '206', '255');
INSERT INTO `ecm_region` VALUES ('220', '池州市', '206', '255');
INSERT INTO `ecm_region` VALUES ('221', '铜陵市', '206', '255');
INSERT INTO `ecm_region` VALUES ('222', '宣城市', '206', '255');
INSERT INTO `ecm_region` VALUES ('223', '黄山市', '206', '255');
INSERT INTO `ecm_region` VALUES ('224', '福建省', '2', '255');
INSERT INTO `ecm_region` VALUES ('225', '福州市', '224', '255');
INSERT INTO `ecm_region` VALUES ('226', '厦门市', '224', '255');
INSERT INTO `ecm_region` VALUES ('227', '莆田市', '224', '255');
INSERT INTO `ecm_region` VALUES ('228', '三明市', '224', '255');
INSERT INTO `ecm_region` VALUES ('229', '泉州市', '224', '255');
INSERT INTO `ecm_region` VALUES ('230', '漳州市', '224', '255');
INSERT INTO `ecm_region` VALUES ('231', '南平市', '224', '255');
INSERT INTO `ecm_region` VALUES ('232', '龙岩市', '224', '255');
INSERT INTO `ecm_region` VALUES ('233', '宁德市', '224', '255');
INSERT INTO `ecm_region` VALUES ('234', '江西省', '2', '255');
INSERT INTO `ecm_region` VALUES ('235', '南昌市', '234', '255');
INSERT INTO `ecm_region` VALUES ('236', '景德镇市', '234', '255');
INSERT INTO `ecm_region` VALUES ('237', '萍乡市', '234', '255');
INSERT INTO `ecm_region` VALUES ('238', '九江市', '234', '255');
INSERT INTO `ecm_region` VALUES ('239', '新余市', '234', '255');
INSERT INTO `ecm_region` VALUES ('240', '鹰潭市', '234', '255');
INSERT INTO `ecm_region` VALUES ('241', '赣州市', '234', '255');
INSERT INTO `ecm_region` VALUES ('242', '吉安市', '234', '255');
INSERT INTO `ecm_region` VALUES ('243', '宜春市', '234', '255');
INSERT INTO `ecm_region` VALUES ('244', '抚州市', '234', '255');
INSERT INTO `ecm_region` VALUES ('245', '上饶市', '234', '255');
INSERT INTO `ecm_region` VALUES ('246', '山东省', '2', '255');
INSERT INTO `ecm_region` VALUES ('247', '济南', '246', '255');
INSERT INTO `ecm_region` VALUES ('248', '青岛', '246', '255');
INSERT INTO `ecm_region` VALUES ('249', '淄博', '246', '255');
INSERT INTO `ecm_region` VALUES ('250', '泰安', '246', '255');
INSERT INTO `ecm_region` VALUES ('251', '济宁', '246', '255');
INSERT INTO `ecm_region` VALUES ('252', '德州', '246', '255');
INSERT INTO `ecm_region` VALUES ('253', '日照', '246', '255');
INSERT INTO `ecm_region` VALUES ('254', '潍坊', '246', '255');
INSERT INTO `ecm_region` VALUES ('255', '枣庄', '246', '255');
INSERT INTO `ecm_region` VALUES ('256', '临沂', '246', '255');
INSERT INTO `ecm_region` VALUES ('257', '莱芜', '246', '255');
INSERT INTO `ecm_region` VALUES ('258', '滨州', '246', '255');
INSERT INTO `ecm_region` VALUES ('259', '聊城', '246', '255');
INSERT INTO `ecm_region` VALUES ('260', '菏泽', '246', '255');
INSERT INTO `ecm_region` VALUES ('261', '烟台', '246', '255');
INSERT INTO `ecm_region` VALUES ('262', '威海', '246', '255');
INSERT INTO `ecm_region` VALUES ('263', '东营', '246', '255');
INSERT INTO `ecm_region` VALUES ('264', '河南省', '2', '255');
INSERT INTO `ecm_region` VALUES ('265', '郑州市', '264', '255');
INSERT INTO `ecm_region` VALUES ('266', '洛阳市', '264', '255');
INSERT INTO `ecm_region` VALUES ('267', '开封市', '264', '255');
INSERT INTO `ecm_region` VALUES ('268', '平顶山市', '264', '255');
INSERT INTO `ecm_region` VALUES ('269', '南阳市', '264', '255');
INSERT INTO `ecm_region` VALUES ('270', '焦作市', '264', '255');
INSERT INTO `ecm_region` VALUES ('271', '信阳市', '264', '255');
INSERT INTO `ecm_region` VALUES ('272', '济源市', '264', '255');
INSERT INTO `ecm_region` VALUES ('273', '周口市', '264', '255');
INSERT INTO `ecm_region` VALUES ('274', '安阳市', '264', '255');
INSERT INTO `ecm_region` VALUES ('275', '驻马店市', '264', '255');
INSERT INTO `ecm_region` VALUES ('276', '新乡市', '264', '255');
INSERT INTO `ecm_region` VALUES ('277', '鹤壁市', '264', '255');
INSERT INTO `ecm_region` VALUES ('278', '商丘市', '264', '255');
INSERT INTO `ecm_region` VALUES ('279', '漯河市', '264', '255');
INSERT INTO `ecm_region` VALUES ('280', '许昌市', '264', '255');
INSERT INTO `ecm_region` VALUES ('281', '三门峡市', '264', '255');
INSERT INTO `ecm_region` VALUES ('282', '濮阳市', '264', '255');
INSERT INTO `ecm_region` VALUES ('283', '湖北省', '2', '255');
INSERT INTO `ecm_region` VALUES ('284', '武汉', '283', '255');
INSERT INTO `ecm_region` VALUES ('285', '宜昌', '283', '255');
INSERT INTO `ecm_region` VALUES ('286', '荆州', '283', '255');
INSERT INTO `ecm_region` VALUES ('287', '十堰', '283', '255');
INSERT INTO `ecm_region` VALUES ('288', '襄樊', '283', '255');
INSERT INTO `ecm_region` VALUES ('289', '黄石', '283', '255');
INSERT INTO `ecm_region` VALUES ('290', '黄冈', '283', '255');
INSERT INTO `ecm_region` VALUES ('291', '恩施', '283', '255');
INSERT INTO `ecm_region` VALUES ('292', '荆门', '283', '255');
INSERT INTO `ecm_region` VALUES ('293', '咸宁', '283', '255');
INSERT INTO `ecm_region` VALUES ('294', '孝感', '283', '255');
INSERT INTO `ecm_region` VALUES ('295', '鄂州', '283', '255');
INSERT INTO `ecm_region` VALUES ('296', '天门', '283', '255');
INSERT INTO `ecm_region` VALUES ('297', '仙桃', '283', '255');
INSERT INTO `ecm_region` VALUES ('298', '随州', '283', '255');
INSERT INTO `ecm_region` VALUES ('299', '潜江', '283', '255');
INSERT INTO `ecm_region` VALUES ('300', '神农架', '283', '255');
INSERT INTO `ecm_region` VALUES ('301', '湖南省', '2', '255');
INSERT INTO `ecm_region` VALUES ('302', '长沙市', '301', '255');
INSERT INTO `ecm_region` VALUES ('303', '株洲市', '301', '255');
INSERT INTO `ecm_region` VALUES ('304', '湘潭市', '301', '255');
INSERT INTO `ecm_region` VALUES ('305', '邵阳市', '301', '255');
INSERT INTO `ecm_region` VALUES ('306', '吉首市', '301', '255');
INSERT INTO `ecm_region` VALUES ('307', '岳阳市', '301', '255');
INSERT INTO `ecm_region` VALUES ('308', '娄底市', '301', '255');
INSERT INTO `ecm_region` VALUES ('309', '怀化市', '301', '255');
INSERT INTO `ecm_region` VALUES ('310', '永州市', '301', '255');
INSERT INTO `ecm_region` VALUES ('311', '郴州市', '301', '255');
INSERT INTO `ecm_region` VALUES ('312', '常德市', '301', '255');
INSERT INTO `ecm_region` VALUES ('313', '衡阳市', '301', '255');
INSERT INTO `ecm_region` VALUES ('314', '益阳市', '301', '255');
INSERT INTO `ecm_region` VALUES ('315', '张家界', '301', '255');
INSERT INTO `ecm_region` VALUES ('316', '湘西州', '301', '255');
INSERT INTO `ecm_region` VALUES ('317', '广东省', '2', '255');
INSERT INTO `ecm_region` VALUES ('318', '广州', '317', '255');
INSERT INTO `ecm_region` VALUES ('319', '深圳', '317', '255');
INSERT INTO `ecm_region` VALUES ('320', '珠海', '317', '255');
INSERT INTO `ecm_region` VALUES ('321', '汕头', '317', '255');
INSERT INTO `ecm_region` VALUES ('322', '佛山', '317', '255');
INSERT INTO `ecm_region` VALUES ('323', '东莞', '317', '255');
INSERT INTO `ecm_region` VALUES ('324', '中山', '317', '255');
INSERT INTO `ecm_region` VALUES ('325', '江门', '317', '255');
INSERT INTO `ecm_region` VALUES ('326', '惠州', '317', '255');
INSERT INTO `ecm_region` VALUES ('327', '肇庆', '317', '255');
INSERT INTO `ecm_region` VALUES ('328', '阳江', '317', '255');
INSERT INTO `ecm_region` VALUES ('329', '韶关', '317', '255');
INSERT INTO `ecm_region` VALUES ('330', '河源', '317', '255');
INSERT INTO `ecm_region` VALUES ('331', '梅州', '317', '255');
INSERT INTO `ecm_region` VALUES ('332', '清远', '317', '255');
INSERT INTO `ecm_region` VALUES ('333', '云浮', '317', '255');
INSERT INTO `ecm_region` VALUES ('334', '茂名', '317', '255');
INSERT INTO `ecm_region` VALUES ('335', '汕尾', '317', '255');
INSERT INTO `ecm_region` VALUES ('336', '揭阳', '317', '255');
INSERT INTO `ecm_region` VALUES ('337', '潮州', '317', '255');
INSERT INTO `ecm_region` VALUES ('338', '湛江', '317', '255');
INSERT INTO `ecm_region` VALUES ('339', '海南省', '2', '255');
INSERT INTO `ecm_region` VALUES ('340', '海口市', '339', '255');
INSERT INTO `ecm_region` VALUES ('341', '三亚市', '339', '255');
INSERT INTO `ecm_region` VALUES ('342', '广西壮族自治区', '2', '255');
INSERT INTO `ecm_region` VALUES ('343', '南宁', '342', '255');
INSERT INTO `ecm_region` VALUES ('344', '柳州', '342', '255');
INSERT INTO `ecm_region` VALUES ('345', '桂林', '342', '255');
INSERT INTO `ecm_region` VALUES ('346', '梧州', '342', '255');
INSERT INTO `ecm_region` VALUES ('347', '北海', '342', '255');
INSERT INTO `ecm_region` VALUES ('348', '防城港', '342', '255');
INSERT INTO `ecm_region` VALUES ('349', '钦州', '342', '255');
INSERT INTO `ecm_region` VALUES ('350', '贵港', '342', '255');
INSERT INTO `ecm_region` VALUES ('351', '玉林', '342', '255');
INSERT INTO `ecm_region` VALUES ('352', '百色', '342', '255');
INSERT INTO `ecm_region` VALUES ('353', '贺州', '342', '255');
INSERT INTO `ecm_region` VALUES ('354', '河池', '342', '255');
INSERT INTO `ecm_region` VALUES ('355', '来宾', '342', '255');
INSERT INTO `ecm_region` VALUES ('356', '崇左', '342', '255');
INSERT INTO `ecm_region` VALUES ('357', '四川省', '2', '255');
INSERT INTO `ecm_region` VALUES ('358', '成都', '357', '255');
INSERT INTO `ecm_region` VALUES ('359', '自贡', '357', '255');
INSERT INTO `ecm_region` VALUES ('360', '攀枝花', '357', '255');
INSERT INTO `ecm_region` VALUES ('361', '泸州', '357', '255');
INSERT INTO `ecm_region` VALUES ('362', '德阳', '357', '255');
INSERT INTO `ecm_region` VALUES ('363', '绵阳', '357', '255');
INSERT INTO `ecm_region` VALUES ('364', '广元', '357', '255');
INSERT INTO `ecm_region` VALUES ('365', '遂宁', '357', '255');
INSERT INTO `ecm_region` VALUES ('366', '内江', '357', '255');
INSERT INTO `ecm_region` VALUES ('367', '资阳', '357', '255');
INSERT INTO `ecm_region` VALUES ('368', '乐山', '357', '255');
INSERT INTO `ecm_region` VALUES ('369', '眉山', '357', '255');
INSERT INTO `ecm_region` VALUES ('370', '南充', '357', '255');
INSERT INTO `ecm_region` VALUES ('371', '宜宾', '357', '255');
INSERT INTO `ecm_region` VALUES ('372', '广安', '357', '255');
INSERT INTO `ecm_region` VALUES ('373', '达州', '357', '255');
INSERT INTO `ecm_region` VALUES ('374', '巴中', '357', '255');
INSERT INTO `ecm_region` VALUES ('375', '雅安', '357', '255');
INSERT INTO `ecm_region` VALUES ('376', '阿坝', '357', '255');
INSERT INTO `ecm_region` VALUES ('377', '甘孜', '357', '255');
INSERT INTO `ecm_region` VALUES ('378', '凉山', '357', '255');
INSERT INTO `ecm_region` VALUES ('379', '贵州省', '2', '255');
INSERT INTO `ecm_region` VALUES ('380', '贵阳市', '379', '255');
INSERT INTO `ecm_region` VALUES ('381', '遵义市', '379', '255');
INSERT INTO `ecm_region` VALUES ('382', '安顺市', '379', '255');
INSERT INTO `ecm_region` VALUES ('383', '六盘水市', '379', '255');
INSERT INTO `ecm_region` VALUES ('384', '毕节地区', '379', '255');
INSERT INTO `ecm_region` VALUES ('385', '铜仁地区', '379', '255');
INSERT INTO `ecm_region` VALUES ('386', '黔东南州', '379', '255');
INSERT INTO `ecm_region` VALUES ('387', '黔南州', '379', '255');
INSERT INTO `ecm_region` VALUES ('388', '黔西南州', '379', '255');
INSERT INTO `ecm_region` VALUES ('389', '云南省', '2', '255');
INSERT INTO `ecm_region` VALUES ('390', '昆明市', '389', '255');
INSERT INTO `ecm_region` VALUES ('391', '曲靖市', '389', '255');
INSERT INTO `ecm_region` VALUES ('392', '红河哈尼族彝族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('393', '昭通市', '389', '255');
INSERT INTO `ecm_region` VALUES ('394', '玉溪市', '389', '255');
INSERT INTO `ecm_region` VALUES ('395', '德宏傣族景颇族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('396', '丽江市', '389', '255');
INSERT INTO `ecm_region` VALUES ('397', '迪庆藏族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('398', '文山壮族苗族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('399', '思茅市', '389', '255');
INSERT INTO `ecm_region` VALUES ('400', '大理白族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('401', '怒江傈僳族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('402', '保山市', '389', '255');
INSERT INTO `ecm_region` VALUES ('403', '楚雄彝族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('404', '西双版纳傣族自治州', '389', '255');
INSERT INTO `ecm_region` VALUES ('405', '临沧市', '389', '255');
INSERT INTO `ecm_region` VALUES ('406', '西藏自治区', '2', '255');
INSERT INTO `ecm_region` VALUES ('407', '拉萨', '406', '255');
INSERT INTO `ecm_region` VALUES ('408', '日喀则', '406', '255');
INSERT INTO `ecm_region` VALUES ('409', '林芝', '406', '255');
INSERT INTO `ecm_region` VALUES ('410', '山南', '406', '255');
INSERT INTO `ecm_region` VALUES ('411', '那曲', '406', '255');
INSERT INTO `ecm_region` VALUES ('412', '昌都', '406', '255');
INSERT INTO `ecm_region` VALUES ('413', '阿里', '406', '255');
INSERT INTO `ecm_region` VALUES ('414', '陕西省', '2', '255');
INSERT INTO `ecm_region` VALUES ('415', '西安市', '414', '255');
INSERT INTO `ecm_region` VALUES ('416', '铜川市', '414', '255');
INSERT INTO `ecm_region` VALUES ('417', '宝鸡市', '414', '255');
INSERT INTO `ecm_region` VALUES ('418', '咸阳市', '414', '255');
INSERT INTO `ecm_region` VALUES ('419', '渭南市', '414', '255');
INSERT INTO `ecm_region` VALUES ('420', '延安市', '414', '255');
INSERT INTO `ecm_region` VALUES ('421', '汉中市', '414', '255');
INSERT INTO `ecm_region` VALUES ('422', '榆林市', '414', '255');
INSERT INTO `ecm_region` VALUES ('423', '安康市', '414', '255');
INSERT INTO `ecm_region` VALUES ('424', '商洛市', '414', '255');
INSERT INTO `ecm_region` VALUES ('425', '甘肃省', '2', '255');
INSERT INTO `ecm_region` VALUES ('426', '兰州市', '425', '255');
INSERT INTO `ecm_region` VALUES ('427', '嘉峪关', '425', '255');
INSERT INTO `ecm_region` VALUES ('428', '金昌市', '425', '255');
INSERT INTO `ecm_region` VALUES ('429', '白银市', '425', '255');
INSERT INTO `ecm_region` VALUES ('430', '天水市', '425', '255');
INSERT INTO `ecm_region` VALUES ('431', '酒泉市', '425', '255');
INSERT INTO `ecm_region` VALUES ('432', '张掖市', '425', '255');
INSERT INTO `ecm_region` VALUES ('433', '武威市', '425', '255');
INSERT INTO `ecm_region` VALUES ('434', '定西市', '425', '255');
INSERT INTO `ecm_region` VALUES ('435', '陇南市', '425', '255');
INSERT INTO `ecm_region` VALUES ('436', '平凉市', '425', '255');
INSERT INTO `ecm_region` VALUES ('437', '庆阳市', '425', '255');
INSERT INTO `ecm_region` VALUES ('438', '临夏州', '425', '255');
INSERT INTO `ecm_region` VALUES ('439', '甘南州', '425', '255');
INSERT INTO `ecm_region` VALUES ('440', '青海省', '2', '255');
INSERT INTO `ecm_region` VALUES ('441', '西宁市', '440', '255');
INSERT INTO `ecm_region` VALUES ('442', '海东行署', '440', '255');
INSERT INTO `ecm_region` VALUES ('443', '海北藏族自治州', '440', '255');
INSERT INTO `ecm_region` VALUES ('444', '海南藏族自治州', '440', '255');
INSERT INTO `ecm_region` VALUES ('445', '海西州', '440', '255');
INSERT INTO `ecm_region` VALUES ('446', '黄南藏族自治州', '440', '255');
INSERT INTO `ecm_region` VALUES ('447', '玉树藏族自治州', '440', '255');
INSERT INTO `ecm_region` VALUES ('448', '果洛藏族自治州', '440', '255');
INSERT INTO `ecm_region` VALUES ('449', '宁夏回族自治区', '2', '255');
INSERT INTO `ecm_region` VALUES ('450', '银川市', '449', '255');
INSERT INTO `ecm_region` VALUES ('451', '石嘴山市', '449', '255');
INSERT INTO `ecm_region` VALUES ('452', '吴忠市', '449', '255');
INSERT INTO `ecm_region` VALUES ('453', '固原市', '449', '255');
INSERT INTO `ecm_region` VALUES ('454', '中卫市', '449', '255');
INSERT INTO `ecm_region` VALUES ('455', '新疆维吾尔自治区', '2', '255');
INSERT INTO `ecm_region` VALUES ('456', '伊犁哈萨克自治州', '455', '255');
INSERT INTO `ecm_region` VALUES ('457', '乌鲁木齐市', '455', '255');
INSERT INTO `ecm_region` VALUES ('458', '昌吉回族自治州', '455', '255');
INSERT INTO `ecm_region` VALUES ('459', '石河子市', '455', '255');
INSERT INTO `ecm_region` VALUES ('460', '克拉玛依市', '455', '255');
INSERT INTO `ecm_region` VALUES ('461', '阿勒泰地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('462', '博尔塔拉蒙古自治州', '455', '255');
INSERT INTO `ecm_region` VALUES ('463', '塔城地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('464', '和田地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('465', '克孜勒苏克尔克孜自治州', '455', '255');
INSERT INTO `ecm_region` VALUES ('466', '喀什地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('467', '阿克苏地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('468', '巴音郭楞蒙古自治州', '455', '255');
INSERT INTO `ecm_region` VALUES ('469', '吐鲁番地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('470', '哈密地区', '455', '255');
INSERT INTO `ecm_region` VALUES ('471', '五家渠市', '455', '255');
INSERT INTO `ecm_region` VALUES ('472', '阿拉尔市', '455', '255');
INSERT INTO `ecm_region` VALUES ('473', '图木舒克市', '455', '255');
INSERT INTO `ecm_region` VALUES ('474', '香港特别行政区', '2', '255');
INSERT INTO `ecm_region` VALUES ('475', '澳门特别行政区', '2', '255');
INSERT INTO `ecm_region` VALUES ('476', '台湾省', '2', '255');

-- ----------------------------
-- Table structure for `ecm_scategory`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_scategory`;
CREATE TABLE `ecm_scategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`cate_id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_scategory
-- ----------------------------
INSERT INTO `ecm_scategory` VALUES ('1', '服饰', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('2', '女装/女士精品', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('3', '男装', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('4', '女鞋', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('5', '流行男鞋', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('6', '运动鞋', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('7', '女士内衣/男士内衣/家居服', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('8', '箱包皮具/热销女包/男包', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('9', '运动服/运动包/颈环配件', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('10', '服饰配件/皮带/帽子/围巾', '1', '255');
INSERT INTO `ecm_scategory` VALUES ('11', '手机/数码/办公/家电', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('12', '手机', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('13', '国货精品手机', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('14', '笔记本电脑', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('15', '电脑硬件/台式整机/网络设备', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('16', 'MP3/MP4/iPod/录音笔', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('17', '数码相机/摄像机/图形冲印', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('18', '3C数码配件市场', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('19', '网络服务/电脑软件', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('20', '闪存卡/U盘/移动存储', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('21', '电玩/配件/游戏/攻略', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('22', '办公设备/文具/耗材', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('23', '影音电器', '11', '255');
INSERT INTO `ecm_scategory` VALUES ('24', '美容护肤/个人护理', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('25', '美容护肤/美体/精油', '24', '255');
INSERT INTO `ecm_scategory` VALUES ('26', '彩妆/香水/美发/工具', '24', '255');
INSERT INTO `ecm_scategory` VALUES ('27', '个人护理/保健/按摩器材', '24', '255');
INSERT INTO `ecm_scategory` VALUES ('28', '家居/母婴/食品', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('29', '居家日用/厨房餐饮/卫浴洗浴', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('30', '时尚家饰/工艺品/十字绣', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('31', '家具/家具定制/宜家代购', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('32', '家纺/床品/地毯/布艺', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('33', '装潢/灯具/五金/安防/卫浴', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('34', '保健食品', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('35', '食品/茶叶/零食/特产', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('36', '奶粉/尿片/母婴用品', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('37', '益智玩具/童车/童床/书包', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('38', '童装/童鞋/孕妇装', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('39', '宠物/宠物食品及用品', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('40', '厨房电器', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('41', '生活电器', '28', '255');
INSERT INTO `ecm_scategory` VALUES ('42', '文体/汽车', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('43', '书籍/杂志/报纸', '42', '255');
INSERT INTO `ecm_scategory` VALUES ('44', '音乐/影视/明星/乐器', '42', '255');
INSERT INTO `ecm_scategory` VALUES ('45', '运动/瑜伽/健身/球迷用品', '42', '255');
INSERT INTO `ecm_scategory` VALUES ('46', '户外/登山/野营/涉水', '42', '255');
INSERT INTO `ecm_scategory` VALUES ('47', '汽车/配件/改装/摩托/自行车', '42', '255');
INSERT INTO `ecm_scategory` VALUES ('48', '珠宝/首饰', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('49', '饰品/流行首饰/时尚饰品', '48', '255');
INSERT INTO `ecm_scategory` VALUES ('50', '珠宝/钻石/翡翠/黄金', '48', '255');
INSERT INTO `ecm_scategory` VALUES ('51', '品牌手表/流行手表', '48', '255');
INSERT INTO `ecm_scategory` VALUES ('52', '收藏/爱好', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('53', '古董/邮币/字画/收藏', '52', '255');
INSERT INTO `ecm_scategory` VALUES ('54', '玩具/模型/娃娃/人偶', '52', '255');
INSERT INTO `ecm_scategory` VALUES ('55', 'ZIPPO/瑞士军刀/眼镜', '52', '255');
INSERT INTO `ecm_scategory` VALUES ('56', '游戏/话费', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('57', '腾讯QQ专区', '56', '255');
INSERT INTO `ecm_scategory` VALUES ('58', '网游装备/游戏币/帐号/代练', '56', '255');
INSERT INTO `ecm_scategory` VALUES ('59', '网络游戏点卡', '56', '255');
INSERT INTO `ecm_scategory` VALUES ('60', '移动/联通/小灵通充值中心', '56', '255');
INSERT INTO `ecm_scategory` VALUES ('61', 'IP卡/网络电话/手机号码', '56', '255');
INSERT INTO `ecm_scategory` VALUES ('62', '生活服务', '0', '255');
INSERT INTO `ecm_scategory` VALUES ('63', '成人用品/避孕用品/情趣内衣', '62', '255');
INSERT INTO `ecm_scategory` VALUES ('64', '网店装修/物流快递/图片存储', '62', '255');
INSERT INTO `ecm_scategory` VALUES ('65', '鲜花速递/蛋糕配送/园艺花艺', '62', '255');
INSERT INTO `ecm_scategory` VALUES ('66', '演出/旅游/吃喝玩乐折扣券', '62', '255');

-- ----------------------------
-- Table structure for `ecm_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_sessions`;
CREATE TABLE `ecm_sessions` (
  `sesskey` char(32) NOT NULL DEFAULT '',
  `expiry` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `adminid` int(11) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `data` char(255) NOT NULL DEFAULT '',
  `is_overflow` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_sessions
-- ----------------------------
INSERT INTO `ecm_sessions` VALUES ('1c4271e9b68b01483316d31fdb147874', '1471563157', '0', '0', '127.0.0.1', '', '1');

-- ----------------------------
-- Table structure for `ecm_sessions_data`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_sessions_data`;
CREATE TABLE `ecm_sessions_data` (
  `sesskey` varchar(32) NOT NULL DEFAULT '',
  `expiry` int(11) NOT NULL DEFAULT '0',
  `data` longtext NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_sessions_data
-- ----------------------------
INSERT INTO `ecm_sessions_data` VALUES ('1c4271e9b68b01483316d31fdb147874', '1471563157', 'ECMALL_WAP|i:0;admin_info|a:5:{s:7:\"user_id\";s:1:\"1\";s:9:\"user_name\";s:5:\"admin\";s:8:\"reg_time\";s:10:\"1421048268\";s:10:\"last_login\";s:10:\"1471459139\";s:7:\"last_ip\";s:11:\"192.168.1.5\";}user_info|a:6:{s:7:\"user_id\";s:1:\"2\";s:9:\"user_name\";s:6:\"seller\";s:8:\"reg_time\";s:10:\"1421048309\";s:10:\"last_login\";s:10:\"1471480378\";s:7:\"last_ip\";s:9:\"127.0.0.1\";s:8:\"store_id\";s:1:\"2\";}');

-- ----------------------------
-- Table structure for `ecm_sgrade`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_sgrade`;
CREATE TABLE `ecm_sgrade` (
  `grade_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(60) NOT NULL DEFAULT '',
  `goods_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `space_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `skin_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `wap_skin_limit` int(3) NOT NULL,
  `charge` varchar(100) NOT NULL DEFAULT '',
  `need_confirm` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `functions` varchar(255) DEFAULT NULL,
  `skins` text NOT NULL,
  `wap_skins` varchar(255) NOT NULL,
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_sgrade
-- ----------------------------
INSERT INTO `ecm_sgrade` VALUES ('1', '系统默认', '5', '2', '1', '0', '100元/年', '0', '测试用户请选择“默认等级”，可以立即开通。', null, 'default|default', '', '255');
INSERT INTO `ecm_sgrade` VALUES ('2', '认证店铺', '200', '1000', '1', '0', '200元/年', '1', '申请时需要上传身份证和营业执照复印件', 'editor_multimedia,coupon,groupbuy', 'default|default', '', '255');

-- ----------------------------
-- Table structure for `ecm_shipping`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_shipping`;
CREATE TABLE `ecm_shipping` (
  `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_name` varchar(100) NOT NULL DEFAULT '',
  `shipping_desc` varchar(255) DEFAULT NULL,
  `first_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `step_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cod_regions` text,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`shipping_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_shipping
-- ----------------------------
INSERT INTO `ecm_shipping` VALUES ('1', '2', '平邮', '普通包裹邮寄', '5.00', '5.00', null, '1', '255');
INSERT INTO `ecm_shipping` VALUES ('2', '2', '快递', '急速快递公司', '10.00', '10.00', 'a:2:{i:3;s:11:\"中国	北京市\";i:41;s:11:\"中国	上海市\";}', '1', '1');
INSERT INTO `ecm_shipping` VALUES ('3', '2', 'EMS', '中国邮政特快专递，全国范围可达', '20.00', '10.00', null, '1', '3');
INSERT INTO `ecm_shipping` VALUES ('5', '21', '哈哈', '', '1.00', '0.00', 'a:0:{}', '1', '255');

-- ----------------------------
-- Table structure for `ecm_store`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_store`;
CREATE TABLE `ecm_store` (
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_name` varchar(100) NOT NULL DEFAULT '',
  `owner_name` varchar(60) NOT NULL DEFAULT '',
  `owner_card` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `sgrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `apply_remark` varchar(255) NOT NULL DEFAULT '',
  `credit_value` int(10) NOT NULL DEFAULT '0',
  `praise_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `domain` varchar(60) DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `close_reason` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned DEFAULT NULL,
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `certification` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `recommended` tinyint(4) NOT NULL DEFAULT '0',
  `theme` varchar(60) NOT NULL DEFAULT '',
  `wap_theme` varchar(255) NOT NULL,
  `store_banner` varchar(255) DEFAULT NULL,
  `store_logo` varchar(255) DEFAULT NULL,
  `description` text,
  `image_1` varchar(255) NOT NULL DEFAULT '',
  `image_2` varchar(255) NOT NULL DEFAULT '',
  `image_3` varchar(255) NOT NULL DEFAULT '',
  `im_qq` varchar(60) NOT NULL DEFAULT '',
  `im_ww` varchar(60) NOT NULL DEFAULT '',
  `im_msn` varchar(60) NOT NULL DEFAULT '',
  `hot_search` varchar(255) NOT NULL,
  `business_scope` varchar(50) NOT NULL,
  `online_service` varchar(255) NOT NULL,
  `hotline` varchar(255) NOT NULL,
  `pic_slides` text NOT NULL,
  `enable_groupbuy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_radar` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_open_pay` tinyint(3) NOT NULL DEFAULT '0',
  `avg_goods_evaluation` decimal(8,2) NOT NULL,
  `avg_service_evaluation` decimal(8,2) NOT NULL,
  `avg_shipped_evaluation` decimal(8,2) NOT NULL,
  `latlng` varchar(100) NOT NULL,
  PRIMARY KEY (`store_id`),
  KEY `store_name` (`store_name`) USING BTREE,
  KEY `owner_name` (`owner_name`) USING BTREE,
  KEY `region_id` (`region_id`) USING BTREE,
  KEY `domain` (`domain`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_store
-- ----------------------------
INSERT INTO `ecm_store` VALUES ('2', '演示店铺', '张老板', '123456789012345678', '358', '中国	四川省	成都', '', '100088', '010-88886666-8866', '2', '', '6', '85.71', '', '1', '', '1249543819', '0', '', '0', '1', 'default|default', '', 'data/files/store_2/other/store_banner.jpg', 'data/files/store_2/other/store_logo.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', '0', '0.00', '0.00', '0.00', '');
INSERT INTO `ecm_store` VALUES ('21', '冰之渴望', '夏天', '512365981478523698', '42', '中国	上海市	浦东新区', '上海市府东新区', '100000', '13258963652', '2', '', '0', '0.00', '', '1', '', '1469387384', '0', null, '65535', '0', 'default|default', '', 'data/files/store_21/other/store_banner.jpg', 'data/files/store_21/other/store_logo.jpg', '<p>个地方噶的发送到发送到发送到发送到发送到</p>', 'data/files/mall/application/store_21_1.jpg', 'data/files/mall/application/store_21_2.jpg', '', '3131212244', '', '', '', '女装', '', '', '', '1', '0', '0', '0.00', '0.00', '0.00', '');

-- ----------------------------
-- Table structure for `ecm_trans`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_trans`;
CREATE TABLE `ecm_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL,
  `apply_money` decimal(10,2) DEFAULT NULL,
  `trans_money` decimal(10,2) DEFAULT NULL,
  `apply_num` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `apply_type` tinyint(4) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `region_name` char(100) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `rules` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_trans
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_ultimate_store`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_ultimate_store`;
CREATE TABLE `ecm_ultimate_store` (
  `ultimate_id` int(255) NOT NULL AUTO_INCREMENT,
  `brand_id` int(50) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `cate_id` int(50) NOT NULL,
  `store_id` int(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ultimate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_ultimate_store
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_uploaded_file`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_uploaded_file`;
CREATE TABLE `ecm_uploaded_file` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_type` varchar(60) NOT NULL DEFAULT '',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_path` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `belong` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link_url` varchar(100) NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_uploaded_file
-- ----------------------------
INSERT INTO `ecm_uploaded_file` VALUES ('3', '2', 'image/jpeg', '12362', 'e312229073211613.jpg', 'data/files/store_2/goods_179/200908060822598478.jpg', '1249546979', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('4', '2', 'image/jpeg', '12719', '4804c0f3850ea00f.jpg', 'data/files/store_2/goods_197/200908060823178267.jpg', '1249546997', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('5', '2', 'image/jpeg', '7452', '0debd9a9e2670ee3.jpg', 'data/files/store_2/goods_9/200908060823294001.jpg', '1249547009', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('6', '2', 'image/jpeg', '11303', 'cdd6104486b110e6.jpg', 'data/files/store_2/goods_25/200908060823452419.jpg', '1249547025', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('7', '2', 'image/jpeg', '11989', 'ab4ccb7023198236.jpg', 'data/files/store_2/goods_32/200908060823523184.jpg', '1249547032', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('8', '2', 'image/jpeg', '9776', '66253ee68af3b7c8.jpg', 'data/files/store_2/goods_43/200908060824034431.jpg', '1249547043', '2', '1', '');
INSERT INTO `ecm_uploaded_file` VALUES ('9', '2', 'image/jpeg', '15810', '20c422381519c2c2.jpg', 'data/files/store_2/goods_131/200908060828517782.jpg', '1249547331', '2', '2', '');
INSERT INTO `ecm_uploaded_file` VALUES ('10', '2', 'image/jpeg', '17379', '4139a3d3b0c4b37e.jpg', 'data/files/store_2/goods_150/200908060829102798.jpg', '1249547350', '2', '2', '');
INSERT INTO `ecm_uploaded_file` VALUES ('11', '2', 'image/jpeg', '19656', '42d91ab1b33192de.jpg', 'data/files/store_2/goods_170/200908060829308411.jpg', '1249547370', '2', '2', '');
INSERT INTO `ecm_uploaded_file` VALUES ('12', '2', 'image/jpeg', '9576', 'b1b9851f8a31e92d.jpg', 'data/files/store_2/goods_107/200908060831473107.jpg', '1249547507', '2', '3', '');
INSERT INTO `ecm_uploaded_file` VALUES ('13', '2', 'image/jpeg', '8929', 'bf4e91fd54345a9c.jpg', 'data/files/store_2/goods_115/200908060831559591.jpg', '1249547515', '2', '3', '');
INSERT INTO `ecm_uploaded_file` VALUES ('14', '2', 'image/jpeg', '6114', '45493a6f6fec84f2.jpg', 'data/files/store_2/goods_140/200908060832202677.jpg', '1249547540', '2', '3', '');
INSERT INTO `ecm_uploaded_file` VALUES ('15', '2', 'image/jpeg', '6561', '63510a7451dcacab.jpg', 'data/files/store_2/goods_147/200908060832272714.jpg', '1249547548', '2', '3', '');
INSERT INTO `ecm_uploaded_file` VALUES ('16', '2', 'image/jpeg', '22367', '8182647915c83d33.jpg', 'data/files/store_2/goods_66/200908060834263919.jpg', '1249547666', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('17', '2', 'image/jpeg', '22319', '37f593db08b31139.jpg', 'data/files/store_2/goods_87/200908060834479577.jpg', '1249547687', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('18', '2', 'image/jpeg', '20131', '816effe5591ca815.jpg', 'data/files/store_2/goods_105/200908060835054315.jpg', '1249547705', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('19', '2', 'image/jpeg', '20031', '45e6c54d9b324696.jpg', 'data/files/store_2/goods_125/200908060835258625.jpg', '1249547725', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('20', '2', 'image/jpeg', '13511', 'a3836dd5e6541b68.jpg', 'data/files/store_2/goods_141/200908060835411590.jpg', '1249547741', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('21', '2', 'image/jpeg', '19887', '16b171b2bb9956a1.jpg', 'data/files/store_2/goods_155/200908060835558086.jpg', '1249547755', '2', '4', '');
INSERT INTO `ecm_uploaded_file` VALUES ('22', '2', 'image/jpeg', '14406', '81c4c33a4abe35a3.jpg', 'data/files/store_2/goods_70/200908060837502713.jpg', '1249547870', '2', '5', '');
INSERT INTO `ecm_uploaded_file` VALUES ('23', '2', 'image/jpeg', '18618', 'ab46c77e6bf8c22a.jpg', 'data/files/store_2/goods_95/200908060841358079.jpg', '1249548095', '2', '6', '');
INSERT INTO `ecm_uploaded_file` VALUES ('24', '2', 'image/jpeg', '19235', '8f46f0a94d7d8e4b.jpg', 'data/files/store_2/goods_108/200908060841484621.jpg', '1249548108', '2', '6', '');
INSERT INTO `ecm_uploaded_file` VALUES ('25', '2', 'image/jpeg', '20369', '940f52830b31759a.jpg', 'data/files/store_2/goods_124/200908060842042302.jpg', '1249548124', '2', '6', '');
INSERT INTO `ecm_uploaded_file` VALUES ('26', '2', 'image/jpeg', '10992', '17035a2c04152769.jpg', 'data/files/store_2/goods_186/200908060906263554.jpg', '1249549586', '2', '7', '');
INSERT INTO `ecm_uploaded_file` VALUES ('27', '2', 'image/jpeg', '11091', '1ef4f5b42972ed6d.jpg', 'data/files/store_2/goods_13/200908060906532764.jpg', '1249549613', '2', '7', '');
INSERT INTO `ecm_uploaded_file` VALUES ('28', '2', 'image/jpeg', '11786', '849f0be16991cdf0.jpg', 'data/files/store_2/goods_36/200908060907164774.jpg', '1249549636', '2', '7', '');
INSERT INTO `ecm_uploaded_file` VALUES ('29', '2', 'image/jpeg', '11795', '1f142f7aca2b77ba.jpg', 'data/files/store_2/goods_187/200908060909472569.jpg', '1249549787', '2', '8', '');
INSERT INTO `ecm_uploaded_file` VALUES ('30', '2', 'image/jpeg', '11602', '0806158a947e9e0e.jpg', 'data/files/store_2/goods_2/200908060910023266.jpg', '1249549802', '2', '8', '');
INSERT INTO `ecm_uploaded_file` VALUES ('31', '2', 'image/jpeg', '8543', '03b2a4603b85e820.jpg', 'data/files/store_2/goods_98/200908060911381037.jpg', '1249549898', '2', '9', '');
INSERT INTO `ecm_uploaded_file` VALUES ('32', '2', 'image/jpeg', '16022', '521bf6e6c8589e5e.jpg', 'data/files/store_2/goods_128/200908060912082754.jpg', '1249549928', '2', '9', '');
INSERT INTO `ecm_uploaded_file` VALUES ('33', '2', 'image/jpeg', '16935', '7b57b81be56d8cb0.jpg', 'data/files/store_2/goods_69/200908060914291406.jpg', '1249550069', '2', '10', '');
INSERT INTO `ecm_uploaded_file` VALUES ('34', '2', 'image/jpeg', '17495', '47f458fa00e4a99f.jpg', 'data/files/store_2/goods_82/200908060914426191.jpg', '1249550082', '2', '10', '');
INSERT INTO `ecm_uploaded_file` VALUES ('35', '2', 'image/jpeg', '13592', '55217d17c0a54e5c.jpg', 'data/files/store_2/goods_94/200908060914542008.jpg', '1249550094', '2', '10', '');
INSERT INTO `ecm_uploaded_file` VALUES ('36', '2', 'image/jpeg', '17705', '63788145012d6b67.jpg', 'data/files/store_2/goods_126/200908060915269026.jpg', '1249550126', '2', '10', '');
INSERT INTO `ecm_uploaded_file` VALUES ('37', '2', 'image/jpeg', '15711', '95a200317cab0127.jpg', 'data/files/store_2/goods_33/200908060917132087.jpg', '1249550233', '2', '11', '');
INSERT INTO `ecm_uploaded_file` VALUES ('38', '2', 'image/jpeg', '13899', 'f78e1c41eb90dad8.jpg', 'data/files/store_2/goods_123/200908060918436837.jpg', '1249550323', '2', '12', '');
INSERT INTO `ecm_uploaded_file` VALUES ('39', '2', 'image/jpeg', '11798', 'c11d579c21a32178.jpg', 'data/files/store_2/goods_142/200908060919027810.jpg', '1249550342', '2', '12', '');
INSERT INTO `ecm_uploaded_file` VALUES ('40', '2', 'image/jpeg', '11142', '8aa9cf1cbb49a683.jpg', 'data/files/store_2/goods_24/200908060920245196.jpg', '1249550424', '2', '13', '');
INSERT INTO `ecm_uploaded_file` VALUES ('41', '2', 'image/jpeg', '13472', '3b108b157c7dc941.jpg', 'data/files/store_2/goods_43/200908060920437979.jpg', '1249550443', '2', '13', '');
INSERT INTO `ecm_uploaded_file` VALUES ('42', '2', 'image/jpeg', '18693', '39388f9f7b055bad.jpg', 'data/files/store_2/goods_54/200908060920546675.jpg', '1249550454', '2', '13', '');
INSERT INTO `ecm_uploaded_file` VALUES ('43', '2', 'image/jpeg', '16603', '0ff8c40a74c9a226.jpg', 'data/files/store_2/goods_128/200908060922084636.jpg', '1249550528', '2', '14', '');
INSERT INTO `ecm_uploaded_file` VALUES ('44', '2', 'image/jpeg', '14549', '7a8e22cc60f7096e.jpg', 'data/files/store_2/goods_141/200908060922218002.jpg', '1249550541', '2', '14', '');
INSERT INTO `ecm_uploaded_file` VALUES ('45', '2', 'image/jpeg', '19331', '587a5e6b23b02e02.jpg', 'data/files/store_2/goods_29/200908060923496883.jpg', '1249550629', '2', '14', '');
INSERT INTO `ecm_uploaded_file` VALUES ('46', '2', 'image/jpeg', '14786', '62af45e8928f3835.jpg', 'data/files/store_2/goods_147/200908060925471585.jpg', '1249550747', '2', '15', '');
INSERT INTO `ecm_uploaded_file` VALUES ('47', '2', 'image/jpeg', '14423', 'f16628f7bb81e7a3.jpg', 'data/files/store_2/goods_67/200908060927474675.jpg', '1249550867', '2', '16', '');
INSERT INTO `ecm_uploaded_file` VALUES ('48', '2', 'image/jpeg', '17180', '7c86857a689162fe.jpg', 'data/files/store_2/goods_121/200908060932011437.jpg', '1249551121', '2', '17', '');
INSERT INTO `ecm_uploaded_file` VALUES ('49', '2', 'image/jpeg', '15260', 'ab69f24b4f3945e0.jpg', 'data/files/store_2/goods_84/200908060934444841.jpg', '1249551284', '2', '17', '');
INSERT INTO `ecm_uploaded_file` VALUES ('50', '2', 'image/jpeg', '16859', 'e77d1081c91645a8.jpg', 'data/files/store_2/goods_195/200908060936352784.jpg', '1249551395', '2', '18', '');
INSERT INTO `ecm_uploaded_file` VALUES ('51', '2', 'image/jpeg', '13430', '7bc43095c465ebf0.jpg', 'data/files/store_2/goods_8/200908060936481674.jpg', '1249551408', '2', '18', '');
INSERT INTO `ecm_uploaded_file` VALUES ('52', '2', 'image/jpeg', '14187', 'b6781ab419a5089a.jpg', 'data/files/store_2/goods_24/200908060937048695.jpg', '1249551424', '2', '18', '');
INSERT INTO `ecm_uploaded_file` VALUES ('53', '2', 'image/jpeg', '12901', '30f7b98ad565c2d0.jpg', 'data/files/store_2/goods_109/200908060938292631.jpg', '1249551509', '2', '19', '');
INSERT INTO `ecm_uploaded_file` VALUES ('54', '2', 'image/jpeg', '12910', 'd55a5ba6fed7e162.jpg', 'data/files/store_2/goods_124/200908060938443027.jpg', '1249551524', '2', '19', '');
INSERT INTO `ecm_uploaded_file` VALUES ('55', '2', 'image/jpeg', '16186', '5e8e59bd6b611024.jpg', 'data/files/store_2/goods_142/200908060939026685.jpg', '1249551542', '2', '19', '');
INSERT INTO `ecm_uploaded_file` VALUES ('56', '2', 'image/jpeg', '9265', 'f02b3f851f57f2ce.jpg', 'data/files/store_2/goods_143/200908060942233830.jpg', '1249551743', '2', '20', '');
INSERT INTO `ecm_uploaded_file` VALUES ('57', '2', 'image/jpeg', '8483', 'd55955141b9a1f90.jpg', 'data/files/store_2/goods_156/200908060942363092.jpg', '1249551756', '2', '20', '');
INSERT INTO `ecm_uploaded_file` VALUES ('58', '2', 'image/jpeg', '7043', 'be88bb9b556e2009.jpg', 'data/files/store_2/goods_166/200908060942462672.jpg', '1249551766', '2', '20', '');
INSERT INTO `ecm_uploaded_file` VALUES ('59', '2', 'image/jpeg', '11456', '1730d57edea6a55c.jpg', 'data/files/store_2/goods_25/200908060950258122.jpg', '1249552225', '2', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('60', '2', 'image/jpeg', '13215', 'e528ca3eb5748a3c.jpg', 'data/files/store_2/goods_39/200908060950399637.jpg', '1249552239', '2', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('61', '2', 'image/jpeg', '13113', '76a7718b471d6d93.jpg', 'data/files/store_2/goods_55/200908060950555738.jpg', '1249552255', '2', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('62', '2', 'image/jpeg', '11796', '2e1daf9d76edce5b.jpg', 'data/files/store_2/goods_67/200908060951072027.jpg', '1249552267', '2', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('63', '2', 'image/jpeg', '10290', 'b40cc60772351b06.jpg', 'data/files/store_2/goods_147/200908060952274906.jpg', '1249552347', '2', '22', '');
INSERT INTO `ecm_uploaded_file` VALUES ('64', '2', 'image/jpeg', '11026', '4bdd992c49d35190.jpg', 'data/files/store_2/goods_157/200908060952376888.jpg', '1249552357', '2', '22', '');
INSERT INTO `ecm_uploaded_file` VALUES ('65', '2', 'image/jpeg', '16541', '79ef35e6f2e347fa.jpg', 'data/files/store_2/goods_64/200908060954245662.jpg', '1249552464', '2', '23', '');
INSERT INTO `ecm_uploaded_file` VALUES ('66', '2', 'image/jpeg', '16314', '4eed1f55e152588a.jpg', 'data/files/store_2/goods_72/200908060954323544.jpg', '1249552472', '2', '23', '');
INSERT INTO `ecm_uploaded_file` VALUES ('67', '2', 'image/jpeg', '23479', 'c9e640d5eb45e295.jpg', 'data/files/store_2/goods_86/200908060954465326.jpg', '1249552486', '2', '23', '');
INSERT INTO `ecm_uploaded_file` VALUES ('68', '2', 'image/jpeg', '16676', '5278b03233b52a17.jpg', 'data/files/store_2/goods_20/200908060957002218.jpg', '1249552620', '2', '24', '');
INSERT INTO `ecm_uploaded_file` VALUES ('69', '2', 'image/jpeg', '12124', '7b2d5ffeff2b1f0e.jpg', 'data/files/store_2/goods_139/200908060958592106.jpg', '1249552739', '2', '25', '');
INSERT INTO `ecm_uploaded_file` VALUES ('70', '2', 'image/jpeg', '14064', '73b603b1799e1457.jpg', 'data/files/store_2/goods_151/200908060959114414.jpg', '1249552751', '2', '25', '');
INSERT INTO `ecm_uploaded_file` VALUES ('71', '2', 'image/jpeg', '14539', '47f6ec2e8d9b2c82.jpg', 'data/files/store_2/goods_166/200908060959265796.jpg', '1249552766', '2', '25', '');
INSERT INTO `ecm_uploaded_file` VALUES ('72', '2', 'image/jpeg', '10242', '7905b61346259857.jpg', 'data/files/store_2/goods_47/200908061000474424.jpg', '1249552847', '2', '26', '');
INSERT INTO `ecm_uploaded_file` VALUES ('73', '2', 'image/jpeg', '11232', 'ae4916f90e5227e9.jpg', 'data/files/store_2/goods_57/200908061000576924.jpg', '1249552857', '2', '26', '');
INSERT INTO `ecm_uploaded_file` VALUES ('74', '2', 'image/jpeg', '12251', '25e53455ff1b63a4.jpg', 'data/files/store_2/goods_71/200908061001114276.jpg', '1249552871', '2', '26', '');
INSERT INTO `ecm_uploaded_file` VALUES ('75', '2', 'image/jpeg', '13586', 'd814a77dc9c54b7e.jpg', 'data/files/store_2/goods_86/200908061001263175.jpg', '1249552886', '2', '26', '');
INSERT INTO `ecm_uploaded_file` VALUES ('76', '2', 'image/jpeg', '11068', '6ae3ade81393e9ac.jpg', 'data/files/store_2/goods_5/200908061003253339.jpg', '1249553005', '2', '27', '');
INSERT INTO `ecm_uploaded_file` VALUES ('77', '2', 'image/jpeg', '9507', '688b8de7d9bc833a.jpg', 'data/files/store_2/goods_18/200908061003382600.jpg', '1249553018', '2', '27', '');
INSERT INTO `ecm_uploaded_file` VALUES ('78', '2', 'image/jpeg', '12437', 'f7881551d7148623.jpg', 'data/files/store_2/goods_29/200908061003494534.jpg', '1249553029', '2', '27', '');
INSERT INTO `ecm_uploaded_file` VALUES ('79', '2', 'image/jpeg', '12528', 'f15ea9ecafbdaf73.jpg', 'data/files/store_2/goods_115/200908061005154170.jpg', '1249553115', '2', '28', '');
INSERT INTO `ecm_uploaded_file` VALUES ('80', '2', 'image/jpeg', '10964', 'fae2eea4ee2c75b6.jpg', 'data/files/store_2/goods_14/200908061006541461.jpg', '1249553214', '2', '28', '');
INSERT INTO `ecm_uploaded_file` VALUES ('81', '2', 'image/jpeg', '12046', 'b75c0a159f3c10b5.jpg', 'data/files/store_2/goods_26/200908061007068653.jpg', '1249553226', '2', '28', '');
INSERT INTO `ecm_uploaded_file` VALUES ('82', '2', 'image/jpeg', '13297', '23141e259bb47c34.jpg', 'data/files/store_2/goods_121/200908061008412008.jpg', '1249553321', '2', '29', '');
INSERT INTO `ecm_uploaded_file` VALUES ('83', '2', 'image/jpeg', '11197', '946a7039481ebbd5.jpg', 'data/files/store_2/goods_127/200908061008473587.jpg', '1249553327', '2', '29', '');
INSERT INTO `ecm_uploaded_file` VALUES ('111', '21', 'image/jpeg', '0', '3.jpg', 'data/files/store_21/other/201607281741578704.jpg', '1469670117', '3', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('109', '21', 'image/jpeg', '8818', '3.jpg', 'data/files/store_21/other/201607281741216984.jpg', '1469670081', '3', '21', '');
INSERT INTO `ecm_uploaded_file` VALUES ('108', '21', 'image/jpeg', '47664', '12 (2).jpg', 'data/files/store_21/goods_100/201607281728201032.jpg', '1469669300', '2', '0', '');
INSERT INTO `ecm_uploaded_file` VALUES ('107', '21', 'image/jpeg', '47664', '12 (2).jpg', 'data/files/store_21/goods_94/201607251121348958.jpg', '1469388094', '2', '39', '');

-- ----------------------------
-- Table structure for `ecm_user_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_user_coupon`;
CREATE TABLE `ecm_user_coupon` (
  `user_id` int(10) unsigned NOT NULL,
  `coupon_sn` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`,`coupon_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_user_coupon
-- ----------------------------
INSERT INTO `ecm_user_coupon` VALUES ('22', '000000053221');

-- ----------------------------
-- Table structure for `ecm_user_grade`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_user_grade`;
CREATE TABLE `ecm_user_grade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` char(32) NOT NULL,
  `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '等级优先级',
  `upgrade` varchar(1000) NOT NULL COMMENT '升级配置',
  `other` varchar(1000) NOT NULL COMMENT '其他相关项，如等级提成配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_user_grade
-- ----------------------------
INSERT INTO `ecm_user_grade` VALUES ('1', '普通会员', '1', 'a:1:{s:3:\"buy\";d:0;}', 'a:2:{s:6:\"buy_tc\";d:0.01;s:7:\"sell_tc\";d:0.01;}');
INSERT INTO `ecm_user_grade` VALUES ('2', '一星会员', '2', 'a:1:{s:3:\"buy\";d:1000;}', 'a:2:{s:6:\"buy_tc\";d:0.02;s:7:\"sell_tc\";d:0.02;}');
INSERT INTO `ecm_user_grade` VALUES ('3', '二星会员', '3', 'a:1:{s:3:\"buy\";d:10000;}', 'a:2:{s:6:\"buy_tc\";d:0.04;s:7:\"sell_tc\";d:0.04;}');
INSERT INTO `ecm_user_grade` VALUES ('4', '三星会员', '4', 'a:1:{s:3:\"buy\";d:100000;}', 'a:2:{s:6:\"buy_tc\";d:0.06;s:7:\"sell_tc\";d:0.06;}');
INSERT INTO `ecm_user_grade` VALUES ('5', '四星会员', '5', 'a:1:{s:3:\"buy\";d:1000000;}', 'a:2:{s:6:\"buy_tc\";d:0.08;s:7:\"sell_tc\";d:0.08;}');
INSERT INTO `ecm_user_grade` VALUES ('6', '五星会员', '6', 'a:1:{s:3:\"buy\";d:10000000;}', 'a:2:{s:6:\"buy_tc\";d:0.10;s:7:\"sell_tc\";d:0.10;}');

-- ----------------------------
-- Table structure for `ecm_user_priv`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_user_priv`;
CREATE TABLE `ecm_user_priv` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `privs` text NOT NULL,
  PRIMARY KEY (`user_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_user_priv
-- ----------------------------
INSERT INTO `ecm_user_priv` VALUES ('1', '0', 'all');
INSERT INTO `ecm_user_priv` VALUES ('2', '2', 'all');
INSERT INTO `ecm_user_priv` VALUES ('21', '21', 'all');

-- ----------------------------
-- Table structure for `ecm_wap_index`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wap_index`;
CREATE TABLE `ecm_wap_index` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cate_id` smallint(4) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned DEFAULT '1',
  `add_time` int(12) DEFAULT NULL,
  `recom_id` int(10) DEFAULT NULL,
  `gcategory_id` int(10) DEFAULT NULL,
  `num` tinyint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wap_index
-- ----------------------------
INSERT INTO `ecm_wap_index` VALUES ('1', 'hdp-test1', 'data/files/mall/wap/recom/1.jpg', '', '1', '0', '1', '1470598866', '0', '0', '6');
INSERT INTO `ecm_wap_index` VALUES ('2', 'hdp-test2', 'data/files/mall/wap/recom/2.jpg', '', '1', '0', '1', '1470598895', '0', '0', '0');
INSERT INTO `ecm_wap_index` VALUES ('3', 'tjdz-test1', 'data/files/mall/wap/recom/3.jpg', '', '2', '0', '1', '1470599137', '17', '21', '0');
INSERT INTO `ecm_wap_index` VALUES ('4', 'tjdz-test2', 'data/files/mall/wap/recom/4.jpg', '', '2', '0', '1', '1470599162', '11', '1', '0');
INSERT INTO `ecm_wap_index` VALUES ('5', 'tjfl-test1', 'data/files/mall/wap/recom/5.jpg', '', '3', '0', '1', '1470599614', '11', '21', '6');
INSERT INTO `ecm_wap_index` VALUES ('6', 'tjfl-test2', 'data/files/mall/wap/recom/6.jpg', '', '3', '0', '1', '1470599723', '11', '1242', '6');

-- ----------------------------
-- Table structure for `ecm_wheel`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wheel`;
CREATE TABLE `ecm_wheel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `tags` char(250) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `content` text,
  `add_time` int(11) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `max_num` int(11) DEFAULT NULL,
  `everper` int(11) DEFAULT NULL,
  `wheel_type` tinyint(4) DEFAULT NULL,
  `default_image` char(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wheel
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_wxconfig`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wxconfig`;
CREATE TABLE `ecm_wxconfig` (
  `w_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `appid` varchar(255) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  `access_token` varchar(512) DEFAULT NULL,
  `access_expire` int(11) DEFAULT NULL,
  `refresh_token` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`w_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wxconfig
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_wxfile`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wxfile`;
CREATE TABLE `ecm_wxfile` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `file_type` varchar(60) NOT NULL,
  `file_size` int(10) NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wxfile
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_wxkeyword`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wxkeyword`;
CREATE TABLE `ecm_wxkeyword` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `kename` varchar(300) DEFAULT NULL,
  `kecontent` varchar(500) DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:文本 2：图文',
  `kyword` varchar(255) DEFAULT NULL,
  `titles` varchar(1000) DEFAULT NULL,
  `imageinfo` varchar(1000) DEFAULT NULL,
  `linkinfo` varchar(1000) DEFAULT NULL,
  `ismess` tinyint(1) DEFAULT NULL,
  `isfollow` tinyint(1) DEFAULT NULL,
  `iskey` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wxkeyword
-- ----------------------------

-- ----------------------------
-- Table structure for `ecm_wxmenu`
-- ----------------------------
DROP TABLE IF EXISTS `ecm_wxmenu`;
CREATE TABLE `ecm_wxmenu` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `tags` varchar(50) DEFAULT NULL,
  `pid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `spid` varchar(50) DEFAULT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `likes` varchar(100) DEFAULT NULL,
  `weixin_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:click 1:viwe',
  `ordid` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `weixin_status` tinyint(1) NOT NULL DEFAULT '0',
  `weixin_keyword` varchar(255) DEFAULT NULL COMMENT '关键词',
  `weixin_key` varchar(255) DEFAULT NULL COMMENT 'key值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecm_wxmenu
-- ----------------------------
