<?php

/**
 * 广告挂件
 *
 */
class AdvtWidget extends BaseWidget
{
    var $_name = 'advt';

    function _get_data()
    {
        $this->options = stripslashes_deep($this->options);
        $today = local_date('Y-m-d');
        $this->options['is_valid'] = (empty($this->options['start_date']) || $this->options['start_date'] <= $today) && 
            (empty($this->options['end_date']) || $this->options['end_date'] >= $today);

        return $this->options;
    }
    
    function get_config_datasrc()
    {
        $this->options = stripslashes_deep($this->options);
        $this->assign('options', $this->options);
    }

    function parse_config($input)
    {
        $result = array();

        if (!empty($input['start_date']))
        {
            $start_date = strtotime($input['start_date']);
            if ($start_date)
            {
                $result['start_date'] = date('Y-m-d', $start_date);
            }
        }
        if (!empty($input['end_date']))
        {
            $end_date = strtotime($input['end_date']);
            if ($end_date)
            {
                $result['end_date'] = date('Y-m-d', $end_date);
            }
        }
        $style = $result['style'] = $input['style'];
        if ($style == 'code')
        {
            $result['html'] = $input['html'];
        }
        elseif ($style == 'text')
        {
            $result['title'] = $input['title'];
            $result['link1'] = $input['link1'];
            $result['size']  = $input['size'];
        }
        elseif ($style == 'image')
        {
            $result['url1']   = $input['url1'];
            $result['link2']  = $input['link2'];
            $result['width1'] = $input['width1'];
            $result['height1']= $input['height1'];
            $result['alt']    = $input['alt'];
        }
        elseif ($style == 'flash')
        {
            $result['url2']   = $input['url2'];
            $result['width2'] = $input['width2'];
            $result['height2']= $input['height2'];
        }
        return $result;
    }
}

?>