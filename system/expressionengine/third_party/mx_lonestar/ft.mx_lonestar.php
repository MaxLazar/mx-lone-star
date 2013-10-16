<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


require_once PATH_THIRD . 'mx_lonestar/config.php';


/**
 *  MX LoneStar Class for ExpressionEngine2
 *
 * @package  ExpressionEngine
 * @subpackage Fieldtypes
 * @category Fieldtypes
 * @author    Max Lazar <max@eec.ms>
 * @copyright Copyright (c) 2013 Max Lazar
 * @Commercial - please see LICENSE file included with this distribution
 */

class Mx_lonestar_ft extends EE_Fieldtype
{
    /**
     * Fieldtype Info
     * @var array
     */
    
    public $info = array('name' => MX_LONESTAR_NAME, 'version' => MX_LONESTAR_VER);
    
    // Parser Flag (preparse pairs?)
    var $has_array_data = true;
    
    /**
     * PHP5 construct
     */
    function __construct()
    {
        parent::EE_Fieldtype();
        $this->EE->lang->loadfile(MX_LONESTAR_KEY);
        
    }
    
    // --------------------------------------------------------------------
    
    function validate($data)
    {
        $valid = TRUE;
        
    }
    
    // --------------------------------------------------------------------
    
    public function display_field($data, $cell = false)
    {
        $r = "";
        
        if (!isset($this->EE->session->cache[__CLASS__]['header'])) {
            $this->EE->cp->add_to_head('<script type="text/javascript" src="' . $this->EE->config->item('theme_folder_url') . 'third_party/mx_lonestar/js/jquery.mx_lonestar.min.js"></script>');
            $this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . $this->EE->config->item('theme_folder_url') . 'third_party/mx_lonestar/css/style.css" />');
            
            $this->EE->session->cache[__CLASS__]['header'] = true;
        }
        
        if ($cell && !isset($this->EE->session->cache[__CLASS__]['matrix'])) {
            $this->EE->cp->add_to_foot('<script type="text/javascript" src="' . $this->EE->config->item('theme_folder_url') . 'third_party/mx_lonestar/js/matrix2.js"></script>');
            $this->EE->session->cache[__CLASS__]['matrix'] = true;
        }
        ;
        
        $is_grid = isset($this->settings['grid_field_id']);
        
        $field_name = $cell ? $this->cell_name : $this->field_name;
        
        $climit = (isset($this->settings['climit'])) ? $this->settings['climit'] : "0";
        $rlimit = (isset($this->settings['rlimit'])) ? $this->settings['rlimit'] : "0";
        
        $name = str_replace(array(
            '[',
            ']'
        ), '_', $this->field_name);
        
        $data_chb = array(
            'name' => $field_name,
            'id' => $name,
            'value' => 'y',
            'checked' => $data,
            'style' => 'margin:10px',
            'class' => 'lonestar',
            'data-climit' => $climit,
            'data-rlimit' => $rlimit
        );
        
        $r = form_checkbox($data_chb);
        
        if (!$cell && !$is_grid) {
            $this->_insert_js('jQuery(document).ready(function() {$("#' . $name . '").mx_lonestar();	});
			');
            
        }
        
        if (!ee()->session->cache(__CLASS__, 'grid_js_loaded') && $is_grid) {
            ee()->javascript->output('

				Grid.bind("mx_lonestar", "display", function(cell)
				{
					cell.find("input").mx_lonestar();
				});

			');
            
            ee()->session->set_cache(__CLASS__, 'grid_js_loaded', TRUE);
        }
        
        
        return $r;
    }
    
    /**
     * replace_tag function.
     *
     * @access public
     * @param mixed $data
     * @param string $params (default: '')
     * @param string $tagdata (default: '')
     * @return void
     */
    function replace_tag($data, $params = '', $tagdata = '')
    {
        return $data;
    }
    
    /**
     * Displays the cell
     *
     * @access public
     * @param $data The cell data
     */
    public function display_cell($data)
    {
        return $this->display_field($data, TRUE);
    }
    
    /**
     * Display Cell Settings
     *
     * @access public
     * @param $cell_settings array The cell settings
     * @return array Label and form inputs
     */
    public function display_cell_settings($cell_settings)
    {
        
        $this->EE->lang->loadfile('mx_lonestar');
        
        $data = array_merge(array(
            'climit' => '',
            'rlimit' => '',
            'default' => 'off'
        ), $cell_settings);
        
        return array(
            array(
                lang('climit', 'climit'),
                form_input(MX_LONESTAR_KEY . '[climit]', $data['climit'])
            ),
            array(
                lang('rlimit', 'rlimit'),
                form_input(MX_LONESTAR_KEY . '[rlimit]', $data['rlimit'])
            )
        );
    }
    // --------------------------------------------------------------------
    
    function display_settings($data)
    {
        
        return;
    }
    
    
    /**
     * save function.
     *
     * @access public
     * @param mixed $data
     * @return void
     */
    function save($data)
    {
        
        return $data;
    }
    
    /**
     * save_cell function.
     *
     * @access public
     * @param mixed $data
     * @return void
     */
    function save_cell($data)
    {
        return $this->save($data);
    }
    
    // --------------------------------------------------------------------
    function install()
    {
        return array();
        
    }
    /**
     * Save Cell Settings
     */
    function save_cell_settings($settings)
    {
        
        return $settings[MX_LONESTAR_KEY];
    }
    
    function save_settings($settings)
    {
        
        $settings = $this->EE->input->post(MX_LONESTAR_KEY);
        
        $settings['field_fmt']      = 'none';
        $settings['field_show_fmt'] = 'n';
        $settings['field_type']     = 'mx_lonestar';
        
        return $settings;
    }
    // --------------------------------------------------------------------
    private function _insert_js($js)
    {
        $this->EE->cp->add_to_foot('<script type="text/javascript">' . $js . '</script>');
    }
    
    // --------------------------------------------------------------------
    public function grid_display_field($data)
    {
        return $this->display_field($data, FALSE);
    }
    // --------------------------------------------------------------------
    function grid_save($data)
    {
        
        return $data;
    }
    
    // --------------------------------------------------------------------
    
    function grid_display_settings($data)
    {
        $this->EE->lang->loadfile('mx_lonestar');
        
        $data = array_merge(array(
            'climit' => '',
            'rlimit' => '',
            'default' => 'off'
        ), $data);
        
        return array(
            $this->grid_settings_row(lang('climit', 'climit'), form_input('climit', $data['climit'])),
            $this->grid_settings_row(lang('rlimit', 'rlimit'), form_input('rlimit', $data['rlimit']))
        );
    }
    
    // --------------------------------------------------------------------
    
    function _get_field_options($data)
    {
        
        return;
    }
    // --------------------------------------------------------------------
    
    /**
     * Grid settings validation callback; makes sure there are file upload
     * directories available before allowing a new file field to be saved
     *
     * @param	array	Grid settings
     * @return	mixed	Validation error or TRUE if passed
     */
    function grid_validate_settings($data)
    {
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------
    function grid_save_settings($data)
    {
        
        return $data;
    }
    
    
    /**
     * Accept all content types.
     *
     * @param string  The name of the content type
     * @return bool   Accepts all content types
     */
    public function accepts_content_type($name)
    {
        return TRUE;
    }
    
}

// END mx_lone_star_ft class

/* End of file ft.mx_lone_star.php */
/* Location: ./expressionengine/third_party/mx_lone_star/ft.mx_lone_star.php */