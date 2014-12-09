<?php

class CFMModelManage_cfm {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_rows_data() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . esc_html($_POST['search_value']) . '%"' : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'asc');
    $order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html($_POST['order_by']) != '') ? esc_html($_POST['order_by']) : 'id') . ' ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . "contactformmaker " . $where . $order_by . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_row_data($id) {
    global $wpdb;
    if ($id != 0) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'contactformmaker WHERE id="%d"', $id));
      $labels2 = array();
      $label_id = array();
      $label_order_original = array(); 
      $label_type = array();
      $label_all = explode('#****#', $row->label_order);
      $label_all = array_slice($label_all, 0, count($label_all) - 1);
  		foreach ($label_all as $key => $label_each) {
        $label_id_each = explode('#**id**#', $label_each);
        array_push($label_id, $label_id_each[0]);
        $label_oder_each = explode('#**label**#', $label_id_each[1]);
        array_push($label_order_original, addslashes($label_oder_each[0]));
        array_push($label_type, $label_oder_each[1]);
  		}
      $labels2['id'] = '"' . implode('","', $label_id) . '"';
      $labels2['label'] = '"' . implode('","', $label_order_original) . '"';
      $labels2['type'] = '"' . implode('","', $label_type) . '"';
      $ids = array();
      $types = array();
      $labels = array();
      $paramss = array();
      $fields = explode('*:*new_field*:*', $row->form_fields);
      $fields = array_slice($fields, 0, count($fields) - 1);   
      foreach ($fields as $field) {
        $temp = explode('*:*id*:*', $field);
        array_push($ids, $temp[0]);
        $temp = explode('*:*type*:*', $temp[1]);
        array_push($types, $temp[0]);
        $temp = explode('*:*w_field_label*:*', $temp[1]);
        array_push($labels, $temp[0]);
        array_push($paramss, $temp[1]);
      }
      $form = $row->form_front;
      foreach ($ids as $ids_key => $id) {
        $label = $labels[$ids_key];
        $type = $types[$ids_key];
        $params = $paramss[$ids_key];
        if (strpos($form, '%' . $id . ' - ' . $label . '%') || strpos($form, '%' . $id . ' -' . $label . '%')) {
          $rep = '';
          $param = array();
          $param['attributes'] = '';
          switch ($type) {
            case 'type_section_break': {
              $params_names = array('w_editor');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              $rep ='<div id="wdform_field'.$id.'" type="type_section_break" class="wdform_field_section_break"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span><div id="'.$id.'_element_sectionform_id_temp" align="left" class="wdform_section_break">'.html_entity_decode($param['w_editor']).'</div></div>';
              break;
            }
            case 'type_editor': {
              $params_names = array('w_editor');
              $temp = $params;
              foreach ($params_names as $params_name ) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              $rep ='<div id="wdform_field'.$id.'" type="type_editor" class="wdform_field" style="display: table-cell;">'.html_entity_decode($param['w_editor']).'</div><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span>';
              break;
            }
            case 'type_send_copy': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_first_val', 'w_required');
              $temp = $params;
              foreach ($params_names as $params_name ) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {	
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");	
              $input_active = ($param['w_first_val'] == 'true' ? "checked='checked'" : "");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_send_copy" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_send_copy" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="checkbox" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" onclick="set_checked(&quot;'.$id.'&quot;,&quot;&quot;,&quot;form_id_temp&quot;)" '.$input_active.' '.$param['attributes'].'></div></div>';
              break;
            }
            case 'type_text': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_first_val', 'w_title', 'w_required', 'w_unique');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp = explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_text" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_text" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
              break;
            }
            case 'type_number': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_first_val', 'w_title', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_number" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp"  class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_number" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onkeypress="return check_isnum(event)" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
              break;
            }
            case 'type_password': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_password" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp"  class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_password" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="password" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
              break;
            }
            case 'type_textarea': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size_w', 'w_size_h', 'w_first_val', 'w_title', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_textarea" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display:'.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_textarea" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><textarea class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" title="'.$param['w_title'].'"  onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size_w'].'px; height: '.$param['w_size_h'].'px;" '.$param['attributes'].'>'.$param['w_first_val'].'</textarea></div></div>';
              break;
            }
            case 'type_phone': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_first_val', 'w_title', 'w_mini_labels', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'].' add_'.$attr;
                }
              }
              $w_first_val = explode('***', $param['w_first_val']);
              $w_title = explode('***', $param['w_title']);
              $w_mini_labels = explode('***', $param['w_mini_labels']);
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_phone" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_phone" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><div id="'.$id.'_table_name" style="display: table;"><div id="'.$id.'_tr_name1" style="display: table-row;"><div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.$input_active.'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onkeypress="return check_isnum(event)"style="width: 50px;" '.$param['attributes'].'><span class="wdform_line" style="margin: 0px 4px; padding: 0px;">-</span></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.$input_active.'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onkeypress="return check_isnum(event)"style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div><div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_area_code">'.$w_mini_labels[0].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_phone_number">'.$w_mini_labels[1].'</label></div></div></div></div></div>';
              break;
            }
            case 'type_name': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_first_val', 'w_title', 'w_mini_labels', 'w_size', 'w_name_format', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $w_first_val = explode('***', $param['w_first_val']);
              $w_title = explode('***', $param['w_title']);
              $w_mini_labels = explode('***', $param['w_mini_labels']);
              if ($param['w_name_format'] == 'normal') {
                $w_name_format = '<div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.($w_first_val[0]==$w_title[0] ? "input_deactive" : "input_active").'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"'.$param['attributes'].'></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.($w_first_val[1]==$w_title[1] ? "input_deactive" : "input_active").'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;" '.$param['attributes'].'></div>';
                $w_name_format_mini_labels = '<div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_first">'.$w_mini_labels[1].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_last">'.$w_mini_labels[2].'</label></div></div>';
              }
              else {
                $w_name_format = '<div id="'.$id.'_td_name_input_title" style="display: table-cell;"><input type="text" class="'.($w_first_val[0]==$w_title[0] ? "input_deactive" : "input_active").'" id="'.$id.'_element_titleform_id_temp" name="'.$id.'_element_titleform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" style="margin: 0px 10px 0px 0px; width: 40px;"></div><div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.($w_first_val[1]==$w_title[1] ? "input_deactive" : "input_active").'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.($w_first_val[2]==$w_title[2] ? "input_deactive" : "input_active").'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[2].'" title="'.$w_title[2].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"></div><div id="'.$id.'_td_name_input_middle" style="display: table-cell;"><input type="text" class="'.($w_first_val[3]==$w_title[3] ? "input_deactive" : "input_active").'" id="'.$id.'_element_middleform_id_temp" name="'.$id.'_element_middleform_id_temp" value="'.$w_first_val[3].'" title="'.$w_title[3].'" onfocus="delete_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" style="width: '.$param['w_size'].'px;"></div>';
                $w_name_format_mini_labels ='<div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_title" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_title">'.$w_mini_labels[0].'</label></div><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_first">'.$w_mini_labels[1].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_last">'.$w_mini_labels[2].'</label></div><div id="'.$id.'_td_name_label_middle" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_middle">'.$w_mini_labels[3].'</label></div></div>';
              }
              $rep = '<div id="wdform_field'.$id.'" type="type_name" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_name" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><div id="'.$id.'_table_name" cellpadding="0" cellspacing="0" style="display: table;"><div id="'.$id.'_tr_name1" style="display: table-row;">'.$w_name_format.'    </div>'.$w_name_format_mini_labels.'   </div></div></div>';
              break;
            }
            case 'type_address': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_mini_labels', 'w_disabled_fields', 'w_required', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $w_mini_labels = explode('***', $param['w_mini_labels']);
              $w_disabled_fields = explode('***',$param['w_disabled_fields']);
              $hidden_inputs = '';
              $labels_for_id = array('street1', 'street2', 'city', 'state', 'postal', 'country');
              foreach ($w_disabled_fields as $key => $w_disabled_field) {
                if ($key != 6) {
                  if ($w_disabled_field == 'yes') {
                    $hidden_inputs .= '<input type="hidden" id="'.$id.'_'.$labels_for_id[$key].'form_id_temp" value="'.$w_mini_labels[$key].'" id_for_label="'.($id+$key).'">';
                  }
                }
              }
              $address_fields = '';
              $g = 0;
              if ($w_disabled_fields[0] == 'no') {
                $g += 2;
                $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="'.$id.'_street1form_id_temp" name="'.$id.'_street1form_id_temp" onchange="change_value(&quot;'.$id.'_street1form_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" id="'.$id.'_mini_label_street1" style="display: block;">'.$w_mini_labels[0].'</label></span>';
              }
              if ($w_disabled_fields[1] == 'no') {
                $g += 2;
                $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="'.$id.'_street2form_id_temp" name="'.($id+1).'_street2form_id_temp" onchange="change_value(&quot;'.$id.'_street2form_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_street2">'.$w_mini_labels[1].'</label></span>';
              }
              if ($w_disabled_fields[2] == 'no') {
                $g++;
                $address_fields .= '<span style="float: left; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_cityform_id_temp" name="'.($id+2).'_cityform_id_temp" onchange="change_value(&quot;'.$id.'_cityform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_city">'.$w_mini_labels[2].'</label></span>';
              }
              if ($w_disabled_fields[3] == 'no') {
                $g++;
                if ($w_disabled_fields[5] == 'yes' && $w_disabled_fields[6] == 'yes') {
                  $address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><select type="text" id="'.$id.'_stateform_id_temp" name="'.($id+3).'_stateform_id_temp" onchange="change_value(&quot;'.$id.'_stateform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><option value=""></option><option value="Alabama">Alabama</option><option value="Alaska">Alaska</option><option value="Arizona">Arizona</option><option value="Arkansas">Arkansas</option><option value="California">California</option><option value="Colorado">Colorado</option><option value="Connecticut">Connecticut</option><option value="Delaware">Delaware</option><option value="Florida">Florida</option><option value="Georgia">Georgia</option><option value="Hawaii">Hawaii</option><option value="Idaho">Idaho</option><option value="Illinois">Illinois</option><option value="Indiana">Indiana</option><option value="Iowa">Iowa</option><option value="Kansas">Kansas</option><option value="Kentucky">Kentucky</option><option value="Louisiana">Louisiana</option><option value="Maine">Maine</option><option value="Maryland">Maryland</option><option value="Massachusetts">Massachusetts</option><option value="Michigan">Michigan</option><option value="Minnesota">Minnesota</option><option value="Mississippi">Mississippi</option><option value="Missouri">Missouri</option><option value="Montana">Montana</option><option value="Nebraska">Nebraska</option><option value="Nevada">Nevada</option><option value="New Hampshire">New Hampshire</option><option value="New Jersey">New Jersey</option><option value="New Mexico">New Mexico</option><option value="New York">New York</option><option value="North Carolina">North Carolina</option><option value="North Dakota">North Dakota</option><option value="Ohio">Ohio</option><option value="Oklahoma">Oklahoma</option><option value="Oregon">Oregon</option><option value="Pennsylvania">Pennsylvania</option><option value="Rhode Island">Rhode Island</option><option value="South Carolina">South Carolina</option><option value="South Dakota">South Dakota</option><option value="Tennessee">Tennessee</option><option value="Texas">Texas</option><option value="Utah">Utah</option><option value="Vermont">Vermont</option><option value="Virginia">Virginia</option><option value="Washington">Washington</option><option value="West Virginia">West Virginia</option><option value="Wisconsin">Wisconsin</option><option value="Wyoming">Wyoming</option></select><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_state">'.$w_mini_labels[3].'</label></span>';
                }
                else {
                  $address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_stateform_id_temp" name="'.($id+3).'_stateform_id_temp" onchange="change_value(&quot;'.$id.'_stateform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_state">'.$w_mini_labels[3].'</label></span>';
                }
              }
              if ($w_disabled_fields[4] == 'no') {
                $g++;
                $address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_postalform_id_temp" name="'.($id+4).'_postalform_id_temp" onchange="change_value(&quot;'.$id.'_postalform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_postal">'.$w_mini_labels[4].'</label></span>';
              }
              if ($w_disabled_fields[5] == 'no') {
                $g++;
                $address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><select type="text" id="'.$id.'_countryform_id_temp" name="'.($id+5).'_countryform_id_temp" onchange="change_state_input('.$id.',&quot;form_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><option value=""></option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Colombi">Colombi</option><option value="Comoros">Comoros</option><option value="Congo (Brazzaville)">Congo (Brazzaville)</option><option value="Congo">Congo</option><option value="Costa Rica">Costa Rica</option><option value="Cote d\'Ivoire">Cote d\'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor (Timor Timur)">East Timor (Timor Timur)</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="Gabon">Gabon</option><option value="Gambia, The">Gambia, The</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Greece">Greece</option><option value="Grenada">Grenada</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Honduras">Honduras</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, North">Korea, North</option><option value="Korea, South">Korea, South</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepa">Nepa</option><option value="Netherlands">Netherlands</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Qatar">Qatar</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Vincent">Saint Vincent</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City">Vatican City</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_country">'.$w_mini_labels[5].'</span>';
              }
              $rep = '<div id="wdform_field'.$id.'" type="type_address" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_address" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" name="'.$id.'_disable_fieldsform_id_temp" id="'.$id.'_disable_fieldsform_id_temp" street1="'.$w_disabled_fields[0].'" street2="'.$w_disabled_fields[1].'" city="'.$w_disabled_fields[2].'" state="'.$w_disabled_fields[3].'" postal="'.$w_disabled_fields[4].'" country="'.$w_disabled_fields[5].'" us_states="'.$w_disabled_fields[6].'"><div id="'.$id.'_div_address" style="width: '.$param['w_size'].'px;">'.$address_fields.$hidden_inputs.'</div></div></div>';
              break;
            }
            case 'type_submitter_mail': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_first_val', 'w_title', 'w_required', 'w_unique', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_submitter_mail" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_submitter_mail" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
              break;
            }
            case 'type_checkbox': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_flow', 'w_choices', 'w_choices_checked', 'w_rowcol', 'w_required', 'w_randomize', 'w_allow_other', 'w_allow_other_num', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $param['w_choices']	= explode('***', $param['w_choices']);
              $param['w_choices_checked']	= explode('***',$param['w_choices_checked']);
              foreach ($param['w_choices_checked'] as $key => $choices_checked) {
                if ($choices_checked == 'true') {
                  $param['w_choices_checked'][$key] = 'checked="checked"';
                }
                else {
                  $param['w_choices_checked'][$key] = '';
                }
              }
              $rep = '<div id="wdform_field'.$id.'" type="type_checkbox" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_checkbox" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_randomize'].'" name="'.$id.'_randomizeform_id_temp" id="'.$id.'_randomizeform_id_temp"><input type="hidden" value="'.$param['w_allow_other'].'" name="'.$id.'_allow_otherform_id_temp" id="'.$id.'_allow_otherform_id_temp"><input type="hidden" value="'.$param['w_allow_other_num'].'" name="'.$id.'_allow_other_numform_id_temp" id="'.$id.'_allow_other_numform_id_temp"><input type="hidden" value="'.$param['w_rowcol'].'" name="'.$id.'_rowcol_numform_id_temp" id="'.$id.'_rowcol_numform_id_temp"><div style="display: table;"><div id="'.$id.'_table_little" style="display: table-row-group;" '.($param['w_flow']=='hor' ? 'for_hor="'.$id.'_hor"' : '').'>';
              if ($param['w_flow'] == 'hor') {
                $j = 0;
                for ($i = 0; $i < (int) $param['w_rowcol']; $i++) {
                  $rep .= '<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
                  for ($l = 0; $l <= (int)(count($param['w_choices']) / $param['w_rowcol']); $l++) {
                    if ($j >= count($param['w_choices']) % $param['w_rowcol'] && $l == (int) (count($param['w_choices']) / $param['w_rowcol'])) {
                      continue;
                    }
                    if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';
                    }
                    else {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';		
                    }
                  }
                  $j++;
                  $rep .= '</div>';
                }
              }
              else {
                for ($i = 0; $i < (int) (count($param['w_choices']) / $param['w_rowcol']); $i++) {
                  $rep .= '<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
                  if (count($param['w_choices']) > (int)$param['w_rowcol']) {
                    for ($l = 0; $l < $param['w_rowcol']; $l++) {
                      if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                      else {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                    }
                  }
                  else {
                    for ($l = 0; $l < count($param['w_choices']); $l++) {
                      if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                      else {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                    }
                  }
                  $rep .= '</div>';
                }
                if (count($param['w_choices']) % $param['w_rowcol'] != 0) {
                  $rep .= '<div id="'.$id.'_element_tr'.((int) (count($param['w_choices']) / (int)$param['w_rowcol'])).'" style="display: table-row;">';
                  for ($k = 0; $k < count($param['w_choices']) % $param['w_rowcol']; $k++) {
                    $l = count($param['w_choices']) - count($param['w_choices']) % $param['w_rowcol'] + $k;
                    if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $l) {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp'.$l.'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
                    }
                    else {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp'.$l.'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
                    }
                  }
                  $rep .= '</div>';
                }
              }
              $rep .= '</div></div></div></div>';
              break;
            }
            case 'type_radio': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_flow', 'w_choices', 'w_choices_checked', 'w_rowcol', 'w_required', 'w_randomize', 'w_allow_other', 'w_allow_other_num', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $param['w_choices']	= explode('***', $param['w_choices']);
              $param['w_choices_checked']	= explode('***', $param['w_choices_checked']);
              foreach ($param['w_choices_checked'] as $key => $choices_checked) {
                if ($choices_checked == 'true') {
                  $param['w_choices_checked'][$key] = 'checked="checked"';
                }
                else {
                  $param['w_choices_checked'][$key] = '';
                }
              }              
              $rep = '<div id="wdform_field'.$id.'" type="type_radio" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_radio" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_randomize'].'" name="'.$id.'_randomizeform_id_temp" id="'.$id.'_randomizeform_id_temp"><input type="hidden" value="'.$param['w_allow_other'].'" name="'.$id.'_allow_otherform_id_temp" id="'.$id.'_allow_otherform_id_temp"><input type="hidden" value="'.$param['w_allow_other_num'].'" name="'.$id.'_allow_other_numform_id_temp" id="'.$id.'_allow_other_numform_id_temp"><input type="hidden" value="'.$param['w_rowcol'].'" name="'.$id.'_rowcol_numform_id_temp" id="'.$id.'_rowcol_numform_id_temp"><div style="display: table;"><div id="'.$id.'_table_little" style="display: table-row-group;" '.($param['w_flow']=='hor' ? 'for_hor="'.$id.'_hor"' : '').'>';
              if ($param['w_flow'] == 'hor') {
                $j = 0;
                for ($i = 0; $i < (int) $param['w_rowcol']; $i++) {
                  $rep .= '<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
                  for($l=0; $l<=(int)(count($param['w_choices'])/$param['w_rowcol']); $l++) {
                    if ($j >= count($param['w_choices'])%$param['w_rowcol'] && $l == (int) (count($param['w_choices']) / $param['w_rowcol'])) {
                      continue;
                    }
                    if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';
                    }
                    else {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';
                    }
                  }
                  $j++;
                  $rep .= '</div>';
                }
              }
              else {
                for ($i = 0; $i < (int) (count($param['w_choices']) / $param['w_rowcol']); $i++) {
                  $rep .= '<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
                  if (count($param['w_choices']) > (int) $param['w_rowcol']) {
                    for ($l = 0; $l < $param['w_rowcol']; $l++) {
                      if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                      else {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                    }
                  }
                  else {
                    for ($l = 0; $l < count($param['w_choices']); $l++) {
                      if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == (int) $param['w_rowcol'] * $i + $l) {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                      else {
                        $rep .= '<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
                      }
                    }
                  }
                  $rep .= '</div>';
                }
                if (count($param['w_choices']) % $param['w_rowcol'] != 0) {
                  $rep .= '<div id="'.$id.'_element_tr'.((int)(count($param['w_choices'])/(int)$param['w_rowcol'])).'" style="display: table-row;">';
                  for ($k = 0; $k < count($param['w_choices']) % $param['w_rowcol']; $k++) {
                    $l = count($param['w_choices']) - count($param['w_choices']) % $param['w_rowcol'] + $k;
                    if ($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $l) {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
                    }
                    else {
                      $rep .= '<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch_rad_label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
                    }
                  }
                  $rep .= '</div>';
                }
              }
              $rep  .= '</div></div></div></div>';
              break;
            }
            case 'type_own_select': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_size', 'w_choices', 'w_choices_checked', 'w_choices_disabled', 'w_required', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs	= array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $required_sym = ($param['w_required'] == "yes" ? " *" : "");
              $param['w_choices']	= explode('***', $param['w_choices']);
              $param['w_choices_checked']	= explode('***',$param['w_choices_checked']);
              $param['w_choices_disabled'] = explode('***',$param['w_choices_disabled']);
              foreach ($param['w_choices_checked'] as $key => $choices_checked) {
                if ($choices_checked == 'true') {
                  $param['w_choices_checked'][$key] = 'selected="selected"';
                }
                else {
                  $param['w_choices_checked'][$key] = '';
                }
              }
              $rep = '<div id="wdform_field'.$id.'" type="type_own_select" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; "><input type="hidden" value="type_own_select" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><select id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" onchange="set_select(this)" style="width: '.$param['w_size'].'px;"  '.$param['attributes'].'>';
              foreach ($param['w_choices'] as $key => $choice) {
                if ($param['w_choices_disabled'][$key] == "true") {
                  $choice_value = '';
                }
                else {
                  $choice_value = $choice;
                }
                $rep .= '<option id="'.$id.'_option'.$key.'" value="'.$choice_value.'" onselect="set_select(&quot;'.$id.'_option'.$key.'&quot;)" '.$param['w_choices_checked'][$key].'>'.$choice.'</option>';
              }
              $rep .= '</select></div></div>';
              break;
            }
            case 'type_captcha': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_digit', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $rep = '<div id="wdform_field'.$id.'" type="type_captcha" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display:'.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_captcha" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div style="display: table;"><div style="display: table-row;"><div valign="middle" style="display: table-cell;"><img type="captcha" digit="'.$param['w_digit'].'" src="' . add_query_arg(array('action' => 'ContactFormmakerwdcaptcha', 'digit' => $param['w_digit'], 'i' => 'form_id_temp'), admin_url('admin-ajax.php')) . 'digit='.$param['w_digit'].'" id="_wd_captchaform_id_temp" class="captcha_img" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" '.$param['attributes'].'></div><div valign="middle" style="display: table-cell;"><div class="captcha_refresh" id="_element_refreshform_id_temp" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" '.$param['attributes'].'></div></div></div><div style="display: table-row;"><div style="display: table-cell;"><input type="text" class="captcha_input" id="_wd_captcha_inputform_id_temp" name="captcha_input" style="width: '.($param['w_digit']*10+15).'px;" '.$param['attributes'].'></div></div></div></div></div>';
              break;
            }
            case 'type_recaptcha': {
              $params_names = array('w_field_label_size', 'w_field_label_pos', 'w_public', 'w_private', 'w_theme', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_field_label_pos'] = ($param['w_field_label_pos'] == "left" ? "table-cell" : "block");
              $rep = '<div id="wdform_field'.$id.'" type="type_recaptcha" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="label" style="vertical-align: top;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_recaptcha" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div id="wd_recaptchaform_id_temp" public_key="'.$param['w_public'].'" private_key="'.$param['w_private'].'" theme="'.$param['w_theme'].'" '.$param['attributes'].'><span style="color: red; font-style: italic;">Recaptcha doesn\'t display in back end</span></div></div></div>';
              break;
            }
            case 'type_map': {
              $params_names = array('w_center_x', 'w_center_y', 'w_long', 'w_lat', 'w_zoom', 'w_width', 'w_height', 'w_info', 'w_class');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs	= array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $marker = '';
              $param['w_long'] = explode('***', $param['w_long']);
              $param['w_lat']	= explode('***', $param['w_lat']);
              $param['w_info'] = explode('***', $param['w_info']);
              foreach ($param['w_long'] as $key => $w_long) {
                $marker .= 'long'.$key.'="'.$w_long.'" lat'.$key.'="'.$param['w_lat'][$key].'" info'.$key.'="'.$param['w_info'][$key].'"';
              }
              $rep = '<div id="wdform_field'.$id.'" type="type_map" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;  vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_map" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div id="'.$id.'_elementform_id_temp" zoom="'.$param['w_zoom'].'" center_x="'.$param['w_center_x'].'" center_y="'.$param['w_center_y'].'" style="width: '.$param['w_width'].'px; height: '.$param['w_height'].'px;" '.$marker.' '.$param['attributes'].'></div></div></div>';
              break;
            }
            case 'type_submit_reset': {
              $params_names = array('w_submit_title', 'w_reset_title', 'w_class', 'w_act');
              $temp = $params;
              foreach ($params_names as $params_name) {
                $temp = explode('*:*' . $params_name . '*:*', $temp);
                $param[$params_name] = $temp[0];
                $temp = $temp[1];
              }
              if ($temp) {
                $temp	= explode('*:*w_attr_name*:*', $temp);
                $attrs = array_slice($temp, 0, count($temp) - 1);
                foreach ($attrs as $attr) {
                  $param['attributes'] = $param['attributes'] . ' add_' . $attr;
                }
              }
              $param['w_act'] = ($param['w_act'] == "false" ? 'style="display: none;"' : "");
              $rep = '<div id="wdform_field'.$id.'" type="type_submit_reset" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell; vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">type_submit_reset_'.$id.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_submit_reset" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><button type="button" class="button-submit" id="'.$id.'_element_submitform_id_temp" value="'.$param['w_submit_title'].'" onclick="check_required(&quot;submit&quot;, &quot;form_id_temp&quot;);" '.$param['attributes'].'>'.$param['w_submit_title'].'</button><button type="button" class="button-reset" id="'.$id.'_element_resetform_id_temp" value="'.$param['w_reset_title'].'" onclick="check_required(&quot;reset&quot;);" '.$param['w_act'].' '.$param['attributes'].'>'.$param['w_reset_title'].'</button></div></div>';
              break;
            }
          }
          // Arrows.
          switch ($type) {
            case 'type_submit_reset': {
              $rep = $rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar" style="visibility:hidden;"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align:top; margin-left: 5px;"></div></div>';
              break;
            }
            case 'type_section_break': {
              $rep = $rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows"><div id="edit_'.$id.'" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span></div><div id="X_'.$id.'" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin-left: 5px;"></div><div class="element_toolbar" style="color:red; vertical-align: top;">(section break)</div></div>';
              break;
            }
            case 'type_captcha':
            case 'type_recaptcha': {
              $rep = $rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin-left: 5px;"></div></div>';
              break;
            }
            case 'type_editor': {
              $rep = $rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin-left: 5px;"></div><div class="element_toolbar" style="color:red; vertical-align:top;">(custom HTML)</div></div>';
              break;
            }
            default: {
              $rep = $rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="' . WD_CFM_URL . '/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin-left: 5px;"></div></div>';
              break;
            }
          }
          $form = str_replace('%' . $id . ' - ' . $labels[$ids_key] . '%', $rep, $form);
          $form = str_replace('%' . $id . ' -' . $labels[$ids_key] . '%', $rep, $form);
        }
      }
      $row->form_front = $form;
    }
    else {
      $row = new stdClass();
      $row->id = 0;
      $row->title = '';
      $row->mail = '';
      $row->form_front = '';
      $row->theme = 0;
      $row->submit_text = '';
      $row->url = '';
      $row->submit_text_type = 0;
      $row->script_mail = '';
      $row->script_mail_user = '';
      $row->counter = 0;
      $row->published = 1;
      $row->label_order = '';
      $row->label_order_current = '';
      $row->article_id = 0;
      $row->public_key = '';
      $row->private_key = '';
      $row->recaptcha_theme = '';
      $row->form_fields = '';
      $row->savedb = 1;
      $row->sendemail = 1;
      $row->requiredmark = '*';
      $row->mail_from = '';
      $row->mail_from_name = '';
      $row->reply_to = '';
      $row->send_to = '';
      $row->autogen_layout = 1;
      $row->custom_front = '';
      $row->mail_from_user = '';
      $row->mail_from_name_user = '';
      $row->reply_to_user = '';
      $row->disabled_fields = '';
      $row->mail_cc = '';
      $row->mail_cc_user = '';
      $row->mail_bcc = '';
      $row->mail_bcc_user = '';
      $row->mail_subject = '';
      $row->mail_subject_user = '';
      $row->mail_mode = 1;
      $row->mail_mode_user = 1;
    }
    return $row;
  }

  public function get_theme_rows_data() {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "contactformmaker_themes ORDER BY title");
    return $rows;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . esc_html($_POST['search_value']) . '%"'  : '');
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "contactformmaker " . $where;
    $total = $wpdb->get_var($query);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}