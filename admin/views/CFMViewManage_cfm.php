<?php

class CFMViewManage_cfm {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;

  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
    $search_value = ((isset($_POST['search_value'])) ? esc_html($_POST['search_value']) : '');
    $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) : 'id');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    ?>
    <div style="clear: both; float: left; width: 99%;">
      <div style="float: left; font-size: 14px; font-weight: bold;">
        This section allows you to edit forms.
        <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-contact-form-builder-guide-2.html">Read More in User Manual</a>
      </div>
      <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/files/fromContactFormBuilder.php">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_CFM_URL . '/images/wd_logo.png'; ?>" />
        </a>
      </div>
    </div>
    <form onkeypress="spider_doNothing(event)" class="wrap" id="manage_form" method="post" action="admin.php?page=manage_cfm" style="float: left; width: 99%;">
      <span class="form_maker_icon"></span>
      <h2>Contact Form Builder</h2>
      <div class="tablenav top">
        <?php
        WDW_CFM_Library::search('Title', $search_value, 'manage_form');
        WDW_CFM_Library::html_page_nav($page_nav['total'], $page_nav['limit'], 'manage_form');
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" style="margin:0;"/></th>
          <th class="table_small_col <?php if ($order_by == 'id') { echo $order_class; } ?>">
            <a onclick="spider_set_input_value('task', '');
              spider_set_input_value('order_by', 'id');
              spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'id') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
              spider_form_submit(event, 'manage_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span></a>
          </th>
          <th class="<?php if ($order_by == 'title') { echo $order_class; } ?>">
            <a onclick="spider_set_input_value('task', '');
              spider_set_input_value('order_by', 'title');
              spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'title') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
              spider_form_submit(event, 'manage_form')" href="">
              <span>Title</span><span class="sorting-indicator"></span></a>
          </th>
          <th class="<?php if ($order_by == 'mail') { echo $order_class; } ?>">
            <a onclick="spider_set_input_value('task', '');
              spider_set_input_value('order_by', 'mail');
              spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'mail') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
              spider_form_submit(event, 'manage_form')" href="">
              <span>Email to send submissions to</span><span class="sorting-indicator"></span></a>
          </th>
          <th class="table_xxl_col">Shortcode</th>
          <th class="table_big_col">Preview</th>
          <th class="table_big_col">Edit</th>
          <th class="table_big_col"><a title="Delete selected items" href="" onclick="if (confirm('Do you want to delete selected items?')) {
                                                       spider_set_input_value('task', 'delete_all');
                                                       spider_form_submit(event, 'manage_form');
                                                     } else {
                                                       return false;
                                                     }">Delete</a></th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              $old = '';
              if (isset($row_data->form) && ($row_data->form != '')) {
                $old = '_old';
              }
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="table_small_col check-column">
                  <input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" type="checkbox"/>
                </td>
                <td class="table_small_col"><?php echo $row_data->id; ?></td>
                <td>
                  <a onclick="spider_set_input_value('task', 'edit<?php echo $old; ?>');
                              spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                              spider_form_submit(event, 'manage_form')" href="" title="Edit"><?php echo $row_data->title; ?></a>
                </td>
                <td><?php echo $row_data->mail; ?></td>
                <td class="table_xxl_col" style="padding-left: 0; padding-right: 0;">
                  <input type="text" value='[Contact_Form_Builder id="<?php echo $row_data->id; ?>"]' onclick="spider_select_value(this)" size="29" readonly="readonly" style="padding-left: 1px; padding-right: 1px;"/>
                </td>
                <td class="table_big_col">
                  <a href="<?php echo add_query_arg(array('action' => 'ContactFormMakerPreview', 'form_id' => $row_data->id, 'test_theme' => $row_data->theme, 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" class="thickbox thickbox-preview" title="Form Preview" onclick="return false;">
                    Preview
                  </a>
                </td>
                <td class="table_big_col">
                  <a onclick="spider_set_input_value('task', 'edit<?php echo $old; ?>');
                              spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                              spider_form_submit(event, 'manage_form')" href="">Edit</a>
                </td>
                <td class="table_big_col">
                  <a onclick="spider_set_input_value('task', 'delete');
                              spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                              spider_form_submit(event, 'manage_form')" href="">Delete</a>
                </td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
        </tbody>
      </table>
      <input id="task" name="task" type="hidden" value=""/>
      <input id="current_id" name="current_id" type="hidden" value=""/>
      <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>"/>
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc"/>
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>"/>
    </form>
    <?php
  }

  public function edit($id) {
    $row = $this->model->get_row_data($id);
    $labels = array();
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
    $labels['id'] = '"' . implode('","', $label_id) . '"';
    $labels['label'] = '"' . implode('","', $label_order_original) . '"';
    $labels['type'] = '"' . implode('","', $label_type) . '"';
    $page_title = (($id != 0) ? 'Edit form ' . $row->title : 'Create new form');
    ?>
    <script type="text/javascript">
      var contact_form_maker_plugin_url = "<?php echo WD_CFM_URL; ?>";
    </script>
    <script type="text/javascript">
      form_view = 1;
      form_view_count = 1;
      form_view_max = 1;
      function submitbutton() {
        <?php
        if ($id) {
        ?>
        if (!document.getElementById('araqel') || (document.getElementById('araqel').value == '0')) {
          alert('Please wait while page loading.');
          return false;
        }
        <?php
        }
        ?>
        tox = '';
        form_fields = '';
        disabled_ids = '';
        l_id_array = [<?php echo $labels['id']?>];
        l_label_array = [<?php echo $labels['label']?>];
        l_type_array = [<?php echo $labels['type']?>];
        l_id_removed = [];      
        document.getElementById('take').style.display = "none";
        document.getElementById('saving').style.display = "block";
        remove_whitespace(document.getElementById('take'));
        for (x = 0; x < l_id_array.length; x++) {
          l_id_removed[l_id_array[x]] = true;
        }
        if (document.getElementById('form_id_tempform_view1')) {
          wdform_page = document.getElementById('form_id_tempform_view1');
          remove_whitespace(wdform_page);
          n = wdform_page.childNodes.length - 2;
          for (q = 0; q <= n; q++) {
            if (!wdform_page.childNodes[q].getAttribute("wdid")) {
              wdform_section = wdform_page.childNodes[q];
              for (x = 0; x < wdform_section.childNodes.length; x++) {
                wdform_column = wdform_section.childNodes[x];
                if (wdform_column.firstChild) {
                  for (y=0; y < wdform_column.childNodes.length; y++) {
                    is_in_old = false;
                    wdform_row = wdform_column.childNodes[y];
                    if (wdform_row.nodeType == 3) {
                      continue;
                    }
                    wdid = wdform_row.getAttribute("wdid");
                    if (!wdid) {
                      continue;
                    }
                    l_id = wdid;
                    l_label = document.getElementById(wdid + '_element_labelform_id_temp').innerHTML;
                    l_label = l_label.replace(/(\r\n|\n|\r)/gm," ");
                    wdtype = wdform_row.firstChild.getAttribute('type');
                    if (wdform_row.getAttribute("disabled")) {
                      if (wdtype != "type_address") {
                        disabled_ids += wdid + ',';
                      }
                      else {
                        disabled_ids += wdid + ',' + (parseInt(wdid)+1) + ','+(parseInt(wdid)+2)+ ',' +(parseInt(wdid)+3)+ ',' +(parseInt(wdid)+4)+ ','+(parseInt(wdid)+5) + ',';
                      }
                    }
                    for (z = 0; z < l_id_array.length; z++) {
                      if (l_id_array[z] == wdid) {
                        if (l_type_array[z] == "type_address") {
                          if (document.getElementById(l_id + "_mini_label_street1")) {
                            l_id_removed[l_id_array[z]] = false;
                          }
                          if (document.getElementById(l_id + "_mini_label_street2")) {
                            l_id_removed[parseInt(l_id_array[z]) + 1] = false;
                          }
                          if (document.getElementById(l_id + "_mini_label_city")) {
                            l_id_removed[parseInt(l_id_array[z]) + 2] = false;	
                          }
                          if (document.getElementById(l_id + "_mini_label_state")) {
                            l_id_removed[parseInt(l_id_array[z]) + 3] = false;
                          }
                          if (document.getElementById(l_id+"_mini_label_postal")) {
                            l_id_removed[parseInt(l_id_array[z]) + 4] = false;
                          }
                          if (document.getElementById(l_id+"_mini_label_country")) {
                            l_id_removed[parseInt(l_id_array[z]) + 5] = false;
                          }
                          z = z + 5;
                        }
                        else {
                          l_id_removed[l_id] = false;
                        }
                      }
                    }
                    if (wdtype == "type_address") {
                      addr_id = parseInt(wdid);
                      id_for_country = addr_id;
                      if (document.getElementById(id_for_country + "_mini_label_street1")) {
                        tox = tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_street1").innerHTML + '#**label**#type_address#****#';
                        addr_id++; 
                      }
                      if (document.getElementById(id_for_country + "_mini_label_street2")) {
                        tox = tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_street2").innerHTML + '#**label**#type_address#****#';
                        addr_id++;
                      }
                      if (document.getElementById(id_for_country+"_mini_label_city")) {
                        tox = tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_city").innerHTML + '#**label**#type_address#****#';
                        addr_id++;
                      }
                      if (document.getElementById(id_for_country + "_mini_label_state")) {
                        tox = tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_state").innerHTML + '#**label**#type_address#****#';
                        addr_id++;
                      }
                      if (document.getElementById(id_for_country + "_mini_label_postal")) {
                        tox = tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_postal").innerHTML + '#**label**#type_address#****#';
                        addr_id++;
                      }
                      if (document.getElementById(id_for_country+"_mini_label_country")) {
                        tox=tox + addr_id + '#**id**#' + document.getElementById(id_for_country + "_mini_label_country").innerHTML + '#**label**#type_address#****#';
                      }
                    }
                    else {
                      tox = tox + wdid + '#**id**#' + l_label + '#**label**#' + wdtype + '#****#';
                    }
                    id = wdid;
                    form_fields += wdid + "*:*id*:*";
                    form_fields += wdtype + "*:*type*:*";
                    w_choices = new Array();
                    w_choices_checked = new Array();
                    w_choices_disabled = new Array();
                    w_allow_other_num = 0;
                    w_property = new Array();
                    w_property_type = new Array();
                    w_property_values = new Array();
                    w_choices_price = new Array();
                    if (document.getElementById(id+'_element_labelform_id_temp').innerHTML) {
                      w_field_label = document.getElementById(id + '_element_labelform_id_temp').innerHTML.replace(/(\r\n|\n|\r)/gm," ");
                    }
                    if (document.getElementById(id + '_label_sectionform_id_temp')) {
                      if (document.getElementById(id + '_label_sectionform_id_temp').style.display == "block") {
                        w_field_label_pos = "top";
                      }
                      else {
                        w_field_label_pos = "left";
                      }
                    }
                    if (document.getElementById(id + "_elementform_id_temp")) {
                      s = document.getElementById(id + "_elementform_id_temp").style.width;
                      w_size=s.substring(0,s.length - 2);
                    }
                    if (document.getElementById(id + "_label_sectionform_id_temp")) {
                      s = document.getElementById(id + "_label_sectionform_id_temp").style.width;
                      w_field_label_size = s.substring(0, s.length - 2);
                    }
                    if (document.getElementById(id + "_requiredform_id_temp")) {
                      w_required = document.getElementById(id + "_requiredform_id_temp").value;
                    }
                    if (document.getElementById(id + "_uniqueform_id_temp")) {
                      w_unique = document.getElementById(id + "_uniqueform_id_temp").value;
                    }
                    if (document.getElementById(id + '_label_sectionform_id_temp')) {
                      w_class = document.getElementById(id + '_label_sectionform_id_temp').getAttribute("class");
                      if (!w_class) {
                        w_class = "";
                      }
                    }
                    gen_form_fields();
                    wdform_row.innerHTML = "%" + id + " - " + l_label + "%";
                  }
                }
              }
            }
            else {
              id = wdform_page.childNodes[q].getAttribute("wdid");
              if (wdform_page.childNodes[q].getAttribute("disabled")) {
                disabled_ids += id + ',';
              }
              w_editor = document.getElementById(id + "_element_sectionform_id_temp").innerHTML;
              form_fields += id + "*:*id*:*";
              form_fields += "type_section_break" + "*:*type*:*";
              form_fields += "custom_" + id + "*:*w_field_label*:*";
              form_fields += w_editor + "*:*w_editor*:*";
              form_fields += "*:*new_field*:*";
              wdform_page.childNodes[q].innerHTML = "%" + id + " - " + "custom_" + id + "%";
            }
          }
        }
        document.getElementById('disabled_fields').value = disabled_ids;
        document.getElementById('label_order_current').value = tox;
        for (x = 0; x < l_id_array.length; x++) {
          if (l_id_removed[l_id_array[x]]) {
            tox = tox + l_id_array[x] + '#**id**#' + l_label_array[x] + '#**label**#' + l_type_array[x] + '#****#';
          }
        }
        document.getElementById('label_order').value = tox;
        document.getElementById('form_fields').value = form_fields;
        refresh_();
        return true;
      }

      gen = <?php echo (($id != 0) ? $row->counter : 1); ?>;

      function enable() {
        if (document.getElementById('formMakerDiv').style.display == 'block') {
          jQuery('#formMakerDiv').slideToggle(200);}else{jQuery('#formMakerDiv').slideToggle(400);
        }
        if (document.getElementById('formMakerDiv').offsetWidth) {
          document.getElementById('formMakerDiv1').style.width = (document.getElementById('formMakerDiv').offsetWidth - 60) + 'px';
        }
        if (document.getElementById('formMakerDiv1').style.display == 'block') {
          jQuery('#formMakerDiv1').slideToggle(200);
        }
        else {
          jQuery('#formMakerDiv1').slideToggle(400);
        }
      }
      function enable2() {
        if (document.getElementById('formMakerDiv').style.display == 'block') {
          jQuery('#formMakerDiv').slideToggle(200);
        }
        else {
          jQuery('#formMakerDiv').slideToggle(400);
        }
        if (document.getElementById('formMakerDiv').offsetWidth) {
          document.getElementById('formMakerDiv1').style.width = (document.getElementById('formMakerDiv').offsetWidth - 60) + 'px';
        }
        if (document.getElementById('formMakerDiv1').style.display == 'block') {
          jQuery('#formMakerDiv1').slideToggle(200);
        }
        else {
          jQuery('#formMakerDiv1').slideToggle(400);
        }
      }
    </script>
    <div style="clear: both; float: left; width: 99%;">
      <div style="float: left; font-size: 14px; font-weight: bold;">
        This section allows you to enable/disable fields in your form.
        <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-contact-form-builder-guide-4.html">Read More in User Manual</a>
      </div>
      <div style="float: right; text-align: right;">
        <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/files/fromContactFormBuilder.php">
          <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_CFM_URL . '/images/wd_logo.png'; ?>" />
        </a>
      </div>
    </div>
    <form class="wrap" id="manage_form" method="post" action="admin.php?page=manage_cfm" style="float: left; width: 99%;">
      <h2><?php echo $page_title; ?></h2>
      <div style="float: right; margin: 0 5px 0 0;">
        <input class="button-primary" type="submit" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'form_options');" value="Form Options"/>
        <input class="button-primary" type="submit" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'form_layout');" value="Form Layout"/>
        <?php
        if ($id) {
          ?>
          <input class="button-secondary" type="submit" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'save_as_copy')" value="Save as Copy"/>
          <?php
        }
        ?>
        <input class="button-secondary" type="submit" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'save')" value="Save"/>
        <input class="button-secondary" type="submit" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'apply');" value="Apply"/>
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'cancel')" value="Cancel"/>
      </div>

      <div class="formmaker_table">
        <div style="float: left;">
          <span class="cfm_logo cfm_black">Contact</span><span class="cfm_logo cfm_white">Form</span><span class="cfm_logo cfm_black">Builder</span><br /><br />
          <img src="<?php echo WD_CFM_URL . '/images/contact_form_maker_logo48.png'; ?>" />
        </div>
        <div style="float: right;">
          <span style="font-size: 16.76pt; font-family: tahoma; color: #FFFFFF; vertical-align: middle;">Form title:&nbsp;&nbsp;</span>
          <input id="title" name="title" class="form_maker_title" value="<?php echo $row->title; ?>" />
          <br /><br />
          <img src="<?php echo WD_CFM_URL . '/images/formoptions.png'; ?>" onclick="if (spider_check_required('title', 'Form title') || !submitbutton()) {return false;}; spider_set_input_value('task', 'form_options'); spider_form_submit(event, 'manage_form');" style="cursor: pointer; margin: 10px 0 10px 10px; float: right;"/>
        </div>
      </div>

      <div id="formMakerDiv" onclick="close_window()"></div>
      <div id="formMakerDiv1" style="padding-top: 20px;" align="center">
        <table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%" style="border: 6px #00aeef solid; background-color: #FFF;">
          <tr>
            <td style="padding:0px">
              <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr valign="top">
                  <td width="50%" height="100%" align="left">
                    <div id="edit_table" style="padding:0px; overflow-y:scroll; height:535px"></div>
                  </td>
                  <td align="center" valign="top" style="background: url("<?php echo WD_CFM_URL . '/images/border2.png'; ?>") repeat-y;">&nbsp;</td>
                  <td style="padding:15px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                      <tr>
                        <td align="right">
                          <img alt="ADD" title="add" style="cursor:pointer; vertical-align:middle; margin:5px" src="<?php echo WD_CFM_URL . '/images/save.png'; ?>" onClick="add(0)"/>
                          <img alt="CANCEL" title="cancel" style="cursor: pointer; vertical-align:middle; margin:5px" src="<?php echo WD_CFM_URL . '/images/cancel_but.png'; ?>" onClick="close_window()"/>
                          <hr style=" margin-bottom:10px" />
                        </td>
                      </tr>
                      <tr height="100%" valign="top">
                        <td id="show_table"></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <input type="hidden" id="old" />
        <input type="hidden" id="old_selected" />
        <input type="hidden" id="element_type" />
        <input type="hidden" id="editing_id" />
        <input type="hidden" value="<?php echo WD_CFM_URL; ?>" id="form_plugins_url" />
        <div id="main_editor" style="position: absolute; display: none; z-index: 140;">
          <?php
          if (user_can_richedit()) {
            wp_editor('', 'form_maker_editor', array('teeny' => FALSE, 'textarea_name' => 'form_maker_editor', 'media_buttons' => FALSE, 'textarea_rows' => 5));
          }
          else {
            ?>
            <textarea name="form_maker_editor" id="form_maker_editor" cols="40" rows="5" style="width: 440px; height: 350px;" class="mce_editable" aria-hidden="true"></textarea>
            <?php
          }
          ?>
        </div>
      </div>
      <?php
      if (!function_exists('the_editor')) {
        ?>
        <iframe id="tinymce" style="display: none;"></iframe>
        <?php
      }
      ?>
      <br /><br />
      <fieldset>
        <legend><h2 style="color: #00aeef;">Form</h2></legend>
        <div id="saving" style="display: none;">
          <div id="saving_text">Saving</div>
          <div id="fadingBarsG">
            <div id="fadingBarsG_1" class="fadingBarsG"></div>
            <div id="fadingBarsG_2" class="fadingBarsG"></div>
            <div id="fadingBarsG_3" class="fadingBarsG"></div>
            <div id="fadingBarsG_4" class="fadingBarsG"></div>
            <div id="fadingBarsG_5" class="fadingBarsG"></div>
            <div id="fadingBarsG_6" class="fadingBarsG"></div>
            <div id="fadingBarsG_7" class="fadingBarsG"></div>
            <div id="fadingBarsG_8" class="fadingBarsG"></div>
          </div>
        </div>
          <?php
          if ($id) {
            ?>
            <div id="take">
              <?php
              echo $row->form_front;
              ?>
            </div>
            <?php
          }
          ?>
      </fieldset>
      <input type="hidden" name="form_front" id="form_front" />
      <input type="hidden" name="form_fields" id="form_fields" />
      <input type="hidden" name="public_key" id="public_key" />
      <input type="hidden" name="private_key" id="private_key" />
      <input type="hidden" name="recaptcha_theme" id="recaptcha_theme" />
      <input type="hidden" id="label_order" name="label_order" value="<?php echo $row->label_order; ?>" />
      <input type="hidden" id="label_order_current" name="label_order_current" value="<?php echo $row->label_order_current; ?>" />  
      <input type="hidden" name="counter" id="counter" value="<?php echo $row->counter; ?>" />
      <input type="hidden" id="araqel" value="0" />
      <input type="hidden" name="disabled_fields" id="disabled_fields" value="<?php echo $row->disabled_fields; ?>">
      <?php
      if ($id) {
        ?>
      <script type="text/javascript">
        function formOnload() {
          for (t = 0; t < <?php echo $row->counter; ?>; t++) {
            if (document.getElementById("wdform_field" + t)) {
              if (document.getElementById("wdform_field" + t).parentNode.getAttribute("disabled")) {
                if (document.getElementById("wdform_field" + t).getAttribute("type") != 'type_section_break') {
                  document.getElementById("wdform_field" + t).style.cssText = 'display: table-cell;';
                }
                document.getElementById("disable_field" + t).checked = false;
                document.getElementById("disable_field" + t).setAttribute("title", "Enable the field");
                document.getElementById("wdform_field" + t).parentNode.style.cssText = 'opacity: 0.4;';
              }
              else {
                document.getElementById( "disable_field" + t).checked = true;
              }
            }
          }
          for (t = 0; t < <?php echo $row->counter; ?>; t++) {
            if (document.getElementById(t + "_typeform_id_temp")) {
              if (document.getElementById(t + "_typeform_id_temp").value == "type_map") {
                if_gmap_init(t);
                for (q = 0; q < 20; q++) {
                  if (document.getElementById(t + "_elementform_id_temp").getAttribute("long" + q)) {
                    w_long = parseFloat(document.getElementById(t + "_elementform_id_temp").getAttribute("long" + q));
                    w_lat = parseFloat(document.getElementById(t + "_elementform_id_temp").getAttribute("lat" + q));
                    w_info = parseFloat(document.getElementById(t + "_elementform_id_temp").getAttribute("info" + q));
                    add_marker_on_map(t, q, w_long, w_lat, w_info, false);
                  }
                }
              }
              else if (document.getElementById(t + "_typeform_id_temp").value == "type_name") {
                var myu = t;
                jQuery(document).ready(function() {
                  jQuery("#" + myu + "_mini_label_first").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var first = "<input type='text' id='first' class='first' style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(first);
                      jQuery("input.first").focus();
                      jQuery("input.first").blur(function() {
                        var id_for_blur = document.getElementById('first').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#" + id_for_blur[0] + "_mini_label_first").text(value);
                      });
                    }
                  });
                  jQuery("label#" + myu + "_mini_label_last").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var last = "<input type='text' id='last' class='last'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";	
                      jQuery(this).html(last);			
                      jQuery("input.last").focus();					
                      jQuery("input.last").blur(function() {	
                        var id_for_blur = document.getElementById('last').parentNode.id.split('_');			
                        var value = jQuery(this).val();			
                        jQuery("#" + id_for_blur[0] + "_mini_label_last").text(value);	
                      });
                    }
                  });
                  jQuery("label#" + myu + "_mini_label_title").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var title_ = "<input type='text' id='title_' class='title_'  style='outline:none; border:none; background:none; width:50px;' value=\""+jQuery(this).text()+"\">";	
                      jQuery(this).html(title_);
                      jQuery("input.title_").focus();
                      jQuery("input.title_").blur(function() {
                        var id_for_blur = document.getElementById('title_').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#" + id_for_blur[0] + "_mini_label_title").text(value);
                      });	
                    }
                  });
                  jQuery("label#" + myu + "_mini_label_middle").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var middle = "<input type='text' id='middle' class='middle'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(middle);
                      jQuery("input.middle").focus();
                      jQuery("input.middle").blur(function() {
                        var id_for_blur = document.getElementById('middle').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_middle").text(value);
                      });
                    }
                  });
                });
              }
              else if (document.getElementById(t + "_typeform_id_temp").value == "type_phone") {
                var myu = t;
                jQuery(document).ready(function() {
                  jQuery("label#"+myu+"_mini_label_area_code").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var area_code = "<input type='text' id='area_code' class='area_code' size='10' style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(area_code);
                      jQuery("input.area_code").focus();
                      jQuery("input.area_code").blur(function() {
                        var id_for_blur = document.getElementById('area_code').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_area_code").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_phone_number").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var phone_number = "<input type='text' id='phone_number' class='phone_number'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(phone_number);		
                      jQuery("input.phone_number").focus();
                      jQuery("input.phone_number").blur(function() {
                        var id_for_blur = document.getElementById('phone_number').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_phone_number").text(value);
                      });	
                    }	
                  });
                });
              }
              else if (document.getElementById(t+"_typeform_id_temp").value == "type_address") {
                var myu = t;
                jQuery(document).ready(function() {
                  jQuery("label#"+myu+"_mini_label_street1").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var street1 = "<input type='text' id='street1' class='street1' style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(street1);
                      jQuery("input.street1").focus();
                      jQuery("input.street1").blur(function() {
                        var id_for_blur = document.getElementById('street1').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_street1").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_street2").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var street2 = "<input type='text' id='street2' class='street2'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(street2);
                      jQuery("input.street2").focus();
                      jQuery("input.street2").blur(function() {
                        var id_for_blur = document.getElementById('street2').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_street2").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_city").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var city = "<input type='text' id='city' class='city'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(city);
                      jQuery("input.city").focus();
                      jQuery("input.city").blur(function() {
                        var id_for_blur = document.getElementById('city').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_city").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_state").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var state = "<input type='text' id='state' class='state'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(state);
                      jQuery("input.state").focus();
                      jQuery("input.state").blur(function() {
                        var id_for_blur = document.getElementById('state').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_state").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_postal").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var postal = "<input type='text' id='postal' class='postal'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(postal);
                      jQuery("input.postal").focus();
                      jQuery("input.postal").blur(function() {
                        var id_for_blur = document.getElementById('postal').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_postal").text(value);
                      });
                    }
                  });
                  jQuery("label#"+myu+"_mini_label_country").click(function() {
                    if (jQuery(this).children('input').length == 0) {
                      var country = "<input type='country' id='country' class='country'  style='outline:none; border:none; background:none;' value=\""+jQuery(this).text()+"\">";
                      jQuery(this).html(country);
                      jQuery("input.country").focus();
                      jQuery("input.country").blur(function() {
                        var id_for_blur = document.getElementById('country').parentNode.id.split('_');
                        var value = jQuery(this).val();
                        jQuery("#"+id_for_blur[0]+"_mini_label_country").text(value);
                      });
                    }
                  });
                });
              }
            }
          }
          remove_whitespace(document.getElementById('take'));
          form_view = 1;
          form_view_count = 0;
          document.getElementById('araqel').value = 1;
        }
        function formAddToOnload() {
          if (formOldFunctionOnLoad) {
            formOldFunctionOnLoad();
          }
          formOnload();
        }
        function formLoadBody() {
          formOldFunctionOnLoad = window.onload;
          window.onload = formAddToOnload;
        }
        var formOldFunctionOnLoad = null;
        formLoadBody();
      </script>
        <?php
      }
      ?>
      <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
      <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
      <input type="hidden" id="task" name="task" value=""/>
      <input type="hidden" id="current_id" name="current_id" value="<?php echo $row->id; ?>" />
    </form>
    <?php
  }

  public function form_options($id) {
    $row = $this->model->get_row_data($id);
    $themes = $this->model->get_theme_rows_data();
    $page_title = $row->title . ' form options';
    $label_id = array();
    $label_label = array();
    $label_type = array();
    $label_all = explode('#****#', $row->label_order_current);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ($label_all as $key => $label_each) {
      $label_id_each = explode('#**id**#', $label_each);
      if ($label_id_each[0] == 22) {
        $default_subject = $key;
      }
      array_push($label_id, $label_id_each[0]);
      $label_order_each = explode('#**label**#', $label_id_each[1]);
      array_push($label_label, $label_order_each[0]);
      array_push($label_type, $label_order_each[1]);
    }
    $fields = explode('*:*id*:*type_submitter_mail*:*type*:*', $row->form_fields);
    $fields_count = count($fields);
    $disabled_fields = explode(',', $row->disabled_fields);
    $disabled_fields = array_slice($disabled_fields, 0, count($disabled_fields) - 1);
    $field_exist = array();
    for ($i = 0; $i < count($label_label); $i++) {
      if ($label_type[$i] == "type_submit_reset" || $label_type[$i] == "type_editor" || $label_type[$i] == "type_map" || $label_type[$i] == "type_captcha" || $label_type[$i] == "type_recaptcha" || $label_type[$i] == "type_button" || $label_type[$i] == "type_send_copy" || in_array($label_id[$i], $disabled_fields)) {
        $field_exist[$i] = FALSE;
      }
      else {
        $field_exist[$i] = TRUE;
      }
    }
    ?>
    <script>
      gen = "<?php echo $row->counter; ?>";
      form_view_max = 20;
      function set_preview() {
        jQuery("#preview_form").attr("href", '<?php echo add_query_arg(array('action' => 'ContactFormMakerPreview', 'form_id' => $row->id), admin_url('admin-ajax.php')); ?>&test_theme=' + jQuery("#theme").val() + '&width=1000&height=500&TB_iframe=1');
        jQuery("#edit_css").attr("href", '<?php echo add_query_arg(array('action' => 'ContactFormMakerEditCSS', 'form_id' => $row->id), admin_url('admin-ajax.php')); ?>&id=' + jQuery("#theme").val() + '&width=800&height=500&TB_iframe=1');
      }
    </script>
    <div style="font-size: 14px; font-weight: bold;">
      This section allows you to edit form options.
      <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-contact-form-builder-guide-3.html">Read More in User Manual</a>
    </div>
    <form class="wrap" method="post" action="admin.php?page=manage_cfm" style="width: 99%;" name="adminForm" id="adminForm">
      <h2><?php echo $page_title; ?></h2>
      <div style="float: right; margin: 0 5px 0 0;">
        <input class="button-secondary" type="submit" onclick="if (spider_check_email('mailToAdd') ||
                                                                   spider_check_email('mail_from_other') ||
                                                                   spider_check_email('reply_to_other') ||
                                                                   spider_check_email('mail_cc') ||
                                                                   spider_check_email('mail_bcc') ||
                                                                   spider_check_email('mail_from_user') ||
                                                                   spider_check_email('reply_to_user') ||
                                                                   spider_check_email('mail_cc_user') ||
                                                                   spider_check_email('mail_bcc_user') ||
                                                                   spider_check_email('mail_from') ||
                                                                   spider_check_email('reply_to')) { return false; }; spider_set_input_value('task', 'save_options')" value="Save"/>
        <input class="button-secondary" type="submit" onclick="if (spider_check_email('mailToAdd') ||
                                                                   spider_check_email('mail_from_other') ||
                                                                   spider_check_email('reply_to_other') ||
                                                                   spider_check_email('mail_cc') ||
                                                                   spider_check_email('mail_bcc') ||
                                                                   spider_check_email('mail_from_user') ||
                                                                   spider_check_email('reply_to_user') ||
                                                                   spider_check_email('mail_cc_user') ||
                                                                   spider_check_email('mail_bcc_user') ||
                                                                   spider_check_email('mail_from') ||
                                                                   spider_check_email('reply_to')) { return false; }; spider_set_input_value('task', 'apply_options')" value="Apply"/>
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'cancel_options')" value="Cancel"/>
      </div>
      <div class="submenu-box" style="width: 99%; float: left; margin: 15px 0 0 0;">
        <div class="submenu-pad">
          <ul id="submenu" class="configuration">
            <li>
              <a id="general" class="fm_fieldset_tab" onclick="form_maker_options_tabs('general')" href="#">General Options</a>
            </li>
            <li>
              <a id="custom" class="fm_fieldset_tab" onclick="form_maker_options_tabs('custom')" href="#">Email Options</a>
            </li>
            <li>
              <a id="actions" class="fm_fieldset_tab" onclick="form_maker_options_tabs('actions')" href="#">Actions after Submission</a>
            </li>
          </ul>
        </div>
      </div>
      <fieldset id="general_fieldset" class="adminform fm_fieldset_deactive">
        <legend style="color: #0B55C4; font-weight: bold;">General Options</legend>
        <table class="admintable" style="float: left;">
          <tr valign="top">
            <td class="fm_options_label">
              <label>Published</label>
            </td>
            <td class="fm_options_value">
              <input type="radio" name="published" id="published_yes" value="1" <?php echo ($row->published) ? 'checked="checked"' : ''; ?> /><label for="published_yes">Yes</label>
              <input type="radio" name="published" id="published_no" value="0" <?php echo (!$row->published) ? 'checked="checked"' : ''; ?> /><label for="published_no">No</label>
            </td>
          </tr>
          <tr valign="top">
            <td class="fm_options_label">
              <label>Save data(to database)</label>
            </td>
            <td class="fm_options_value">
              <input type="radio" name="savedb" id="savedb_yes" value="1" <?php echo ($row->savedb) ? 'checked="checked"' : ''; ?> /><label for="savedb_yes">Yes</label>
              <input type="radio" name="savedb" id="savedb_no" value="0" <?php echo (!$row->savedb) ? 'checked="checked"' : ''; ?> /><label for="savedb_no">No</label>
            </td>
          </tr>
          <tr valign="top">
            <td class="fm_options_label">
              <label for="theme">Theme</label>
            </td>
            <td class="fm_options_value">
              <select id="theme" name="theme" style="width:260px;" onChange="set_preview()">
                <?php
                foreach ($themes as $theme) {
                  ?>
                  <option value="<?php echo $theme->id; ?>" <?php echo (($theme->id == $row->theme) ? 'selected="selected"' : ''); ?> <?php echo (($theme->id != 4) ? 'disabled="disabled" title="This theme is disabled in free version."' : ''); ?>><?php echo $theme->title; ?></option>
                  <?php
                }
                ?>
              </select>
              <a href="<?php echo add_query_arg(array('action' => 'ContactFormMakerPreview', 'form_id' => $row->id, 'test_theme' => $row->theme, 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" class="button-primary thickbox thickbox-preview" id="preview_form" title="Form Preview" onclick="return false;">
                Preview
              </a>
              <a onclick="alert('This option is disabled in free version.'); return false;" href="#" class="button-secondary" id="edit_css" title="Edit CSS">
                Edit CSS
              </a>
              <div class="spider_description spider_free_desc">Themes are disabled in free version.</div>
            </td>
          </tr>
          <tr valign="top">
            <td class="fm_options_label">
              <label for="requiredmark">Required fields mark</label>
            </td>
            <td class="fm_options_value">
              <input type="text" id="requiredmark" name="requiredmark" value="<?php echo $row->requiredmark; ?>" style="width: 250px;" />
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset id="custom_fieldset" class="adminform fm_fieldset_deactive">
        <legend style="color: #0B55C4; font-weight: bold;">Email Options</legend>
        <table class="admintable">
          <tr valign="top">
            <td style="padding: 15px;">
              <label>Send E-mail</label>
            </td>
            <td style="padding: 15px;">
              <input type="radio" name="sendemail" id="sendemail_yes" value="1" <?php echo ($row->sendemail) ? 'checked="checked"' : ''; ?> /><label for="sendemail_yes">Yes</label>
              <input type="radio" name="sendemail" id="sendemail_no" value="0" <?php echo (!$row->sendemail) ? 'checked="checked"' : ''; ?> /><label for="sendemail_no">No</label>
            </td>
          </tr>
        </table>
        <fieldset class="fm_mail_options">
          <legend style="color: #0B55C4; font-weight: bold;">Email to Administrator</legend>
          <table class="admintable">
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mailToAdd">Email to send submissions to</label>
              </td>
              <td class="fm_options_value">
                <input type="text" id="mailToAdd" name="mailToAdd" style="width: 250px;" />
                <input type="hidden" id="mail" name="mail" value="<?php echo $row->mail; ?>" />
                <img src="<?php echo WD_CFM_URL . '/images/add.png'; ?>"
                     style="vertical-align: middle; cursor: pointer;"
                     title="Add more emails"
                     onclick="if (spider_check_email('mailToAdd')) {return false;};cfm_create_input('mail', 'mailToAdd', 'cfm_mail_div', '<?php echo WD_CFM_URL; ?>')" />
                <div id="cfm_mail_div">
                  <?php
                  $mail_array = explode(',', $row->mail);
                  foreach ($mail_array as $mail) {
                    if ($mail && $mail != ',') {
                      ?>
                      <div class="fm_mail_input">
                        <?php echo $mail; ?>
                        <img src="<?php echo WD_CFM_URL; ?>/images/delete.png" class="fm_delete_img" onclick="fm_delete_mail(this, '<?php echo $mail; ?>')" title="Delete Email" />
                      </div>
                      <?php
                    }
                  }
                  ?>
                </div>
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mail_from">Email From</label>
              </td>
              <td class="fm_options_value">
                <?php 
                $is_other = TRUE;
                for ($i = 0; $i < $fields_count - 1; $i++) {
                  ?>
                  <div>
                    <input type="radio" name="mail_from" id="mail_from<?php echo $i; ?>" value="<?php echo substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])); ?>"  <?php echo (substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])) == $row->mail_from ? 'checked="checked"' : '' ); ?> onclick="wdhide('mail_from_other')" />
                    <label for="mail_from<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                  </div>
                  <?php
                  if (substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) == $row->mail_from) {
                    $is_other = FALSE;
                  }
                }
                ?>
                <div style="<?php echo ($fields_count == 1) ? 'display:none;' : ''; ?>">
                  <input type="radio" id="other" name="mail_from" value="other" <?php echo ($is_other) ? 'checked="checked"' : ''; ?> onclick="wdshow('mail_from_other')" />
                  <label for="other">Other</label>
                </div>
								<input type="text" style="width: <?php echo ($fields_count == 1) ? '250px' : '235px; margin-left: 15px' ?>; display: <?php echo ($is_other) ? 'block;' : 'none;'; ?>" id="mail_from_other" name="mail_from_other" value="<?php echo ($is_other) ? $row->mail_from : ''; ?>" />
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mail_from_name">From Name</label>
              </td>
              <td class="fm_options_value">
                <input type="text" id="mail_from_name" name="mail_from_name" value="<?php echo $row->mail_from_name; ?>" style="width: 250px;" />
                <img src="<?php echo WD_CFM_URL . '/images/add.png'; ?>" onclick="document.getElementById('mail_from_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none; cursor: pointer;" />
								<div style="position: relative; top: -3px;">
                  <div id="mail_from_labels" class="email_labels" style="display: none;">
                    <?php 
                    $choise = "document.getElementById('mail_from_name')";
                    for ($i = 0; $i < count($label_label); $i++) {
                      if (!$field_exist[$i]) {
                        continue;
                      }
                      $param = htmlspecialchars(addslashes($label_label[$i]));
                      $fld_label = htmlspecialchars($label_label[$i]);
                      if (strlen($fld_label) > 30) {
                        $fld_label = wordwrap($fld_label, 30);
                        $fld_label = explode("\n", $fld_label);
                        $fld_label = $fld_label[0] . ' ...';
                      }
                      ?>
                      <a onClick="insertAtCursor(<?php echo $choise; ?>,'<?php echo $param; ?>'); document.getElementById('mail_from_labels').style.display='none';" style="display: block; text-decoration:none;"><?php echo $fld_label; ?></a>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="reply_to">Reply to<br/>(if different from "Email From") </label>
              </td>
              <td class="fm_options_value">
                <?php 
                $is_other = TRUE;
                for ($i = 0; $i < $fields_count - 1; $i++) {
									?>
                  <div>
                    <input type="radio" name="reply_to" id="reply_to<?php echo $i; ?>" value="<?php echo substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])); ?>"  <?php echo (substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) == $row->reply_to ? 'checked="checked"' : ''); ?> onclick="wdhide('reply_to_other')" />
                    <label for="reply_to<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                  </div>
                  <?php
                  if (substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) == $row->reply_to) {
                    $is_other = FALSE;
                  }
								}
								?>
								<div style="<?php echo ($fields_count == 1) ? 'display: none;' : ''; ?>">
                  <input type="radio" id="other1" name="reply_to" value="other" <?php echo ($is_other) ? 'checked="checked"' : ''; ?> onclick="wdshow('reply_to_other')" />
                  <label for="other1">Other</label>
                </div>
								<input type="text" style="width: <?php echo ($fields_count == 1) ? '250px' : '235px; margin-left: 15px'; ?>; display: <?php echo ($is_other) ? 'block;' : 'none;'; ?>" id="reply_to_other" name="reply_to_other" value="<?php echo ($is_other && $row->reply_to) ? $row->reply_to : ''; ?>" />
              </td>
            </tr>
            <tr valign="top">
							<td class="fm_options_label">
								<label for="mail_cc">CC: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_cc" name="mail_cc" value="<?php echo $row->mail_cc; ?>" style="width: 250px;" />
							</td>
						</tr>
						<tr valign="top">
							<td class="fm_options_label">
								<label for="mail_bcc">BCC: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_bcc" name="mail_bcc" value="<?php echo $row->mail_bcc; ?>" style="width: 250px;" />
							</td>
						</tr>
						<tr valign="top">
							<td class="fm_options_label">
								<label for="mail_subject">Subject: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_subject" name="mail_subject" value="<?php echo (($row->mail_subject == '') && !in_array($label_id[$default_subject], $disabled_fields)) ? '%' . $label_label[$default_subject] . '%' : $row->mail_subject; ?>" style="width: 250px;" />
								<img src="<?php echo WD_CFM_URL . '/images/add.png'; ?>" onclick="document.getElementById('mail_subject_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none; cursor: pointer;" />
								<div style="position: relative; top: -3px;">
                  <div id="mail_subject_labels" class="email_labels" style="display: none;" >
                    <?php
                    $choise = "document.getElementById('mail_subject')";
                    for ($i = 0; $i < count($label_label); $i++) {
                      if (!$field_exist[$i]) {
                        continue;
                      }
                      $param = htmlspecialchars(addslashes($label_label[$i]));
                      $fld_label = htmlspecialchars($label_label[$i]);
                      if (strlen($fld_label) > 30) {
                        $fld_label = wordwrap($fld_label, 30);
                        $fld_label = explode("\n", $fld_label);
                        $fld_label = $fld_label[0] . ' ...';
                      }
                      ?>
                      <a onClick="insertAtCursor(<?php echo $choise; ?>,'<?php echo $param; ?>'); document.getElementById('mail_subject_labels').style.display='none';" style="display: block; text-decoration: none;"><?php echo $fld_label; ?></a>
                      <?php
                    }
                    ?>
                  </div>
                </div>
							</td>
						</tr>
						<tr valign="top">
              <td class="fm_options_label" style="vertical-align: middle;">
                <label>Mode: </label>
              </td>
              <td class="fm_options_value">
                <input type="radio" name="mail_mode" id="htmlmode" value="1" <?php if ($row->mail_mode == 1 ) echo 'checked="checked"'; ?> /><label for="htmlmode">HTML</label>
                <input type="radio" name="mail_mode" id="textmode" value="0" <?php if ($row->mail_mode == 0 ) echo 'checked="checked"'; ?> /><label for="textmode">Text</label>
              </td>
            </tr>
            <tr>
              <td class="fm_options_label" valign="top">
                <label>Custom Text in Email For Administrator</label>
              </td>
              <td class="fm_options_value">
                <div style="margin-bottom:5px">
                  <?php
                  $choise = "document.getElementById('script_mail')";
                  for ($i = 0; $i < count($label_label); $i++) {
                    if (!$field_exist[$i]) {
                      continue;
                    }
                    $param = htmlspecialchars(addslashes($label_label[$i]));
                    ?>
                    <input style="border: 1px solid silver; font-size: 10px;" type="button" value="<?php echo htmlspecialchars($label_label[$i]); ?>" onClick="insertAtCursor(<?php echo $choise; ?>, '<?php echo $param; ?>')" />
                    <?php
                  }
                  ?>
                  <input style="border: 1px solid silver; font-size: 11px; font-weight: bold; display: block;" type="button" value="All fields list" onClick="insertAtCursor(<?php echo $choise; ?>, 'all')" />
                </div>
                <?php
                if (user_can_richedit()) {
                  wp_editor($row->script_mail, 'script_mail', array('teeny' => FALSE, 'textarea_name' => 'script_mail', 'media_buttons' => FALSE, 'textarea_rows' => 5));
                }
                else {
                  ?>
                  <textarea name="script_mail" id="script_mail" cols="20" rows="10" style="width: 300px; height: 450px;"><?php echo $row->script_mail; ?></textarea>
                  <?php
                }
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="fm_mail_options">
          <legend style="color: #0B55C4; font-weight: bold;">Email to User</legend>
          <table class="admintable">
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mail">Send to</label>
              </td>
              <td class="fm_options_value">
                <?php 
								$fields = explode('*:*id*:*type_submitter_mail*:*type*:*', $row->form_fields);
								$fields_count = count($fields);
                if ($fields_count == 1) {
									?>There is no email field<?php
                }
								else {
                  for ($i = 0; $i < $fields_count - 1; $i++) {
                    ?>
                    <div>
                      <input type="checkbox" name="send_to<?php echo $i; ?>" id="send_to<?php echo $i; ?>" value="<?php echo substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])); ?>"  <?php echo (is_numeric(strpos($row->send_to, '*' . substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*') + 15, strlen($fields[$i])) . '*')) ? 'checked="checked"' : '' ); ?> />
                      <label for="send_to<?php echo $i; ?>"><?php echo substr($fields[$i + 1], 0, strpos($fields[$i + 1], '*:*w_field_label*:*')); ?></label>
                    </div>
                    <?php
                  }
								}
                ?>
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mail_from_user">Email From</label>
              </td>
              <td class="fm_options_value">
                <input type="text" id="mail_from_user" name="mail_from_user" value="<?php echo $row->mail_from_user; ?>" style="width: 250px;" />
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="mail_from_name_user">From Name</label>
              </td>
              <td class="fm_options_value">
                <input type="text" id="mail_from_name_user" name="mail_from_name_user" value="<?php echo $row->mail_from_name_user; ?>" style="width: 250px;"/>
                <img src="<?php echo WD_CFM_URL . '/images/add.png'; ?>" onclick="document.getElementById('mail_from_name_user_labels').style.display='block';" style="vertical-align: middle; display: inline-block; margin: 0px; float: none; cursor: pointer;" />
                <div style="position: relative; top: -3px;">
                  <div id="mail_from_name_user_labels" class="email_labels" style="display:none;">
                    <?php 
                    $choise = "document.getElementById('mail_from_name_user')";
                    for ($i = 0; $i < count($label_label); $i++) {
                      if (!$field_exist[$i]) {
                        continue;
                      }
                      $param = htmlspecialchars(addslashes($label_label[$i]));
                      $fld_label = htmlspecialchars($label_label[$i]);
                      if (strlen($fld_label) > 30) {
                        $fld_label = wordwrap($fld_label, 30);
                        $fld_label = explode("\n", $fld_label);
                        $fld_label = $fld_label[0] . ' ...';
                      }
                      ?>
                      <a onClick="insertAtCursor(<?php echo $choise; ?>,'<?php echo $param; ?>'); document.getElementById('mail_from_name_user_labels').style.display='none';" style="display: block; text-decoration: none;"><?php echo $fld_label; ?></a>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </td>
            </tr>
            <tr valign="top">
              <td class="fm_options_label">
                <label for="reply_to_user">Reply to<br />(if different from "Email Form")</label>
              </td>
              <td class="fm_options_value">
                <input type="text" id="reply_to_user" name="reply_to_user" value="<?php echo $row->reply_to_user; ?>" style="width: 250px;" />
              </td>
            </tr>
            <tr valign="top">
							<td class="fm_options_label">
								<label for="mail_cc_user">CC: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_cc_user" name="mail_cc_user" value="<?php echo $row->mail_cc_user; ?>" style="width: 250px;" />
							</td>
						</tr>
						<tr valign="top">
							<td class="fm_options_label">
								<label for="mail_bcc_user">BCC: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_bcc_user" name="mail_bcc_user" value="<?php echo $row->mail_bcc_user; ?>" style="width: 250px;" />
							</td>
						</tr>
						<tr valign="top">
							<td class="fm_options_label">
								<label for="mail_subject_user">Subject: </label>
							</td>
							<td class="fm_options_value">
								<input type="text" id="mail_subject_user" name="mail_subject_user" value="<?php echo (($row->mail_subject_user == '') && !in_array($label_id[$default_subject], $disabled_fields)) ? '%' . $label_label[$default_subject] . '%' : $row->mail_subject_user; ?>" style="width: 250px;" />
								<img src="<?php echo WD_CFM_URL . '/images/add.png'; ?>" onclick="document.getElementById('mail_subject_user_labels').style.display='block';" style="vertical-align: middle; display: inline-block; margin: 0px; float: none; cursor: pointer;" />
								<div style="position: relative; top: -3px;">
                  <div id="mail_subject_user_labels" class="email_labels" style="display: none;">
                  <?php
                  $choise = "document.getElementById('mail_subject_user')";
                  for ($i = 0; $i < count($label_label); $i++) {
                    if (!$field_exist[$i]) {
                      continue;
                    }
                    $param = htmlspecialchars(addslashes($label_label[$i]));
                    $fld_label = htmlspecialchars($label_label[$i]);
                    if (strlen($fld_label) > 30) {
                      $fld_label = wordwrap($fld_label, 30);
                      $fld_label = explode("\n", $fld_label);
                      $fld_label = $fld_label[0] . ' ...';
                    }
                    ?>
                    <a onClick="insertAtCursor(<?php echo $choise; ?>,'<?php echo $param; ?>'); document.getElementById('mail_subject_user_labels').style.display='none';" style="display: block; text-decoration: none;"><?php echo $fld_label; ?></a>
                    <?php
                  }
                  ?>
                  </div>
                </div>
							</td>
						</tr>
						<tr valign="top">
              <td class="fm_options_label" style="vertical-align: middle;">
                <label>Mode: </label>
              </td>
              <td class="fm_options_value">
                <input type="radio" name="mail_mode_user" id="htmlmode_user" value="1" <?php if ($row->mail_mode_user == 1 ) echo 'checked="checked"'; ?> /><label for="htmlmode_user">HTML</label>
                <input type="radio" name="mail_mode_user" id="textmode_user" value="0" <?php if ($row->mail_mode_user == 0 ) echo 'checked="checked"'; ?> /><label for="textmode_user">Text</label>
              </td>
            </tr>
            <tr>
              <td class="fm_options_label" valign="top">
                <label>Custom Text in Email For User</label>
              </td>
              <td class="fm_options_value">
                <div style="margin-bottom:5px">
                  <?php
                  $choise = "document.getElementById('script_mail_user')";
                  for ($i = 0; $i < count($label_label); $i++) {
                    if (!$field_exist[$i]) {
                      continue;
                    }
                    $param = htmlspecialchars(addslashes($label_label[$i]));
                    ?>
                    <input style="border: 1px solid silver; font-size: 10px;" type="button" value="<?php echo htmlspecialchars($label_label[$i]); ?>" onClick="insertAtCursor(<?php echo $choise; ?>, '<?php echo $param; ?>')" />
                    <?php
                  }
                  ?>
                  <input style="border: 1px solid silver; font-size: 11px; font-weight: bold; display: block;" type="button" value="All fields list" onClick="insertAtCursor(<?php echo $choise; ?>, 'all')" />
                </div>
                <?php
                if (user_can_richedit()) {
                  wp_editor($row->script_mail_user, 'script_mail_user', array('teeny' => FALSE, 'textarea_name' => 'script_mail_user', 'media_buttons' => FALSE, 'textarea_rows' => 5));
                }
                else {
                  ?>
                  <textarea name="script_mail_user" id="script_mail_user" cols="20" rows="10" style="width: 300px; height: 450px;"><?php echo $row->script_mail_user; ?></textarea>
                  <?php
                }
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <fieldset id="actions_fieldset" class="adminform fm_fieldset_deactive">
        <legend style="color: #0B55C4; font-weight: bold;">Actions after submission</legend>
        <table class="admintable">
          <tr valign="top">
            <td class="fm_options_label">
              <label>Action type</label>
            </td>
            <td class="fm_options_value">
              <div><input type="radio" name="submit_text_type" id="text_type_none" onclick="set_type('none')" value="1" <?php echo ($row->submit_text_type != 2 && $row->submit_text_type != 3 && $row->submit_text_type != 4 && $row->submit_text_type != 5) ? "checked" : ""; ?> /><label for="text_type_none">Stay on Form</label></div>
              <div><input type="radio" name="submit_text_type" id="text_type_post" onclick="set_type('post')" value="2" <?php echo ($row->submit_text_type == 2) ? "checked" : ""; ?> /><label for="text_type_post">Post</label></label></div>
              <div><input type="radio" name="submit_text_type" id="text_type_page" onclick="set_type('page')" value="5" <?php echo ($row->submit_text_type == 5) ? "checked" : ""; ?> /><label for="text_type_page">Page</label></label></div>
              <div><input type="radio" name="submit_text_type" id="text_type_custom_text" onclick="set_type('custom_text')" value="3" <?php echo ($row->submit_text_type == 3 ) ? "checked" : ""; ?> /><label for="text_type_custom_text">Custom Text</label></label></div>
              <div><input type="radio" name="submit_text_type" id="text_type_url" onclick="set_type('url')" value="4" <?php echo ($row->submit_text_type == 4) ? "checked" : ""; ?> /><label for="text_type_url">URL</div>
            </td>
          </tr>
          <tr id="none" <?php echo (($row->submit_text_type == 2 || $row->submit_text_type == 3 || $row->submit_text_type == 4 || $row->submit_text_type == 5) ? 'style="display:none"' : ''); ?>>
            <td class="fm_options_label">
              <label>Stay on Form</label>
            </td>
            <td class="fm_options_value">
              <img src="<?php echo WD_CFM_URL . '/images/tick.png'; ?>" border="0">
            </td>
          </tr>
          <tr id="post" <?php echo (($row->submit_text_type != 2) ? 'style="display: none"' : ''); ?>>
            <td class="fm_options_label">
              <label for="post_name">Post</label>
            </td>
            <td class="fm_options_value">
              <select id="post_name" name="post_name" style="width: 153px; font-size: 11px;">
                <option value="0">- Select Post -</option>
                <?php
                // The Query.
                $args = array('posts_per_page'  => 10000);
                query_posts($args);
                // The Loop.
                while (have_posts()) : the_post(); ?>
                <option value="<?php $x = get_permalink(get_the_ID()); echo $x; ?>" <?php echo (($row->article_id == $x) ? 'selected="selected"' : ''); ?>><?php the_title(); ?></option>
                <?php
                endwhile;
                // Reset Query.
                wp_reset_query();
                ?>
              </select>
            </td>
          </tr>
          <tr id="page" <?php echo (($row->submit_text_type != 5) ? 'style="display: none"' : ''); ?>>
            <td class="fm_options_label">
              <label for="page_name">Page</label>
            </td>
            <td class="fm_options_value">
              <select id="page_name" name="page_name" style="width: 153px; font-size: 11px;">
                <option value="0">- Select Page -</option>
                <?php
                // The Query.
                $pages = get_pages();
                // The Loop.
                foreach ($pages as $page) {
                  $page_id = get_page_link($page->ID);
                  ?>
                <option value="<?php echo $page_id; ?>" <?php echo (($row->article_id == $page_id) ? 'selected="selected"' : ''); ?>><?php echo $page->post_title; ?></option>
                  <?php
                }
                // Reset Query.
                wp_reset_query();
                ?>
              </select>
            </td>
          </tr>
          <tr id="custom_text" <?php echo (($row->submit_text_type != 3) ? 'style="display: none;"' : ''); ?>>
            <td class="fm_options_label">
              <label for="submit_text">Text</label>
            </td>
            <td class="fm_options_value">
              <?php
              if (user_can_richedit()) {
                wp_editor($row->submit_text, 'submit_text', array('teeny' => FALSE, 'textarea_name' => 'submit_text', 'media_buttons' => FALSE, 'textarea_rows' => 5));
              }
              else {
                ?>
                <textarea cols="36" rows="5" id="submit_text" name="submit_text" style="resize: vertical;">
                  <?php echo $row->submit_text; ?>
                </textarea>
                <?php
              }
              ?>
            </td>
          </tr>
          <tr id="url" <?php echo (($row->submit_text_type != 4 ) ? 'style="display:none"' : ''); ?>>
            <td class="fm_options_label">
              <label for="url">URL</label>
            </td>
            <td class="fm_options_value">
              <input type="text" id="url" name="url" style="width:300px" value="<?php echo $row->url; ?>" />
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="hidden" name="fieldset_id" id="fieldset_id" value="<?php echo WDW_CFM_Library::get('fieldset_id', 'general'); ?>" />
      <input type="hidden" id="task" name="task" value=""/>
      <input type="hidden" id="current_id" name="current_id" value="<?php echo $row->id; ?>" />
    </form>
    <script>
      jQuery(window).load(function () {
        form_maker_options_tabs(jQuery("#fieldset_id").val());
        spider_popup();
        jQuery("#mail_from_labels, #mail_from_name_user_labels, #mail_subject_labels, #mail_subject_user_labels").mouseleave(function() {
          jQuery(this).hide();
        });
      });
    </script>
    <?php
  }

  public function form_layout($id) {
    $row = $this->model->get_row_data($id);
    $ids = array();
    $types = array();
    $labels = array();
    $fields = explode('*:*new_field*:*', $row->form_fields);
    $fields = array_slice($fields, 0, count($fields) - 1);
    foreach ($fields as $field) {
      $temp = explode('*:*id*:*', $field);
      array_push($ids, $temp[0]);
      $temp = explode('*:*type*:*', $temp[1]);
      array_push($types, $temp[0]);
      $temp = explode('*:*w_field_label*:*', $temp[1]);
      array_push($labels, $temp[0]);
    }
		?>
    <script>
      var form_front = '<?php echo addslashes($row->form_front);?>';
      var custom_front = '<?php echo addslashes($row->custom_front);?>';
      if (custom_front == '') {
        custom_front = form_front;
      }
      function submitbutton() {
        if (jQuery('#autogen_layout').is(':checked')) {
          jQuery('#custom_front').val(custom_front.replace(/\s+/g, ' ').replace(/> </g, '><'));
        }
        else {
          jQuery('#custom_front').val(editor.getValue().replace(/\s+/g, ' ').replace(/> </g, '><'));
        }
      }
      function insertAtCursor_form(myId, myLabel) {
        if (jQuery('#autogen_layout').is(':checked')) {
          alert("Uncheck the Auto-Generate Layout box.");
          return;
        }
        myValue = '<div wdid="' + myId + '" class="wdform_row">%' + myId + ' - ' + myLabel + '%</div>';
        line = editor.getCursor().line;
        ch = editor.getCursor().ch;
        text = editor.getLine(line);
        text1 = text.substr(0, ch);
        text2 = text.substr(ch);
        text = text1 + myValue + text2;
        editor.setLine(line, text);
        editor.focus();
      }
      function autogen(status) {
        if (status) {
          custom_front = editor.getValue();
          editor.setValue(form_front);
          editor.setOption('readOnly', true);
          autoFormat();
        }
        else {
          editor.setValue(custom_front);
          editor.setOption('readOnly', false);
          autoFormat();
        }
      }
      function autoFormat() {
        CodeMirror.commands["selectAll"](editor);
        editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
        editor.scrollTo(0,0);
      }
    </script>

    <div class="fm_layout" style="width: 99%;">
      <form action="admin.php?page=manage_cfm" method="post" name="adminForm" enctype="multipart/form-data">
        <div class="buttons_div">
          <input class="button-secondary" type="submit" onclick="submitbutton(); spider_set_input_value('task', 'save_layout')" value="Save"/>
          <input class="button-secondary" type="submit" onclick="submitbutton(); spider_set_input_value('task', 'apply_layout')" value="Apply"/>
          <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'cancel_options')" value="Cancel"/>
        </div>
        <h2 style="clear: both;">Description</h2>
        <p>To customize the layout of the form fields uncheck the Auto-Generate Layout box and edit the HTML.</p>
        <p>You can change positioning, add in-line styles and etc. Click on the provided buttons to add the corresponding field.<br /> This will add the following line:
          <b><span class="cm-tag">&lt;div</span> <span class="cm-attribute">wdid</span>=<span class="cm-string">"example_id"</span> <span class="cm-attribute">class</span>=<span class="cm-string">"wdform_row"</span><span class="cm-tag">&gt;</span>%example_id - Example%<span class="cm-tag">&lt;/div&gt;</span></b>
          , where <b><span class="cm-tag">&lt;div&gt;</span></b> is used to set a row.</p>
        <p>To return to the default settings you should check Auto-Generate Layout box.</p>
        <h3 style="color:red">Notice</h3>
        <p>Make sure not to publish the same field twice. This will cause malfunctioning of the form.</p>
        <hr/>
        <label for="autogen_layout" style="font-size: 20px; line-height: 45px; margin-left: 10px;">Auto Generate Layout? </label>
        <input type="checkbox" value="1" name="autogen_layout" id="autogen_layout" <?php echo (($row->autogen_layout) ? 'checked="checked"' : ''); ?> />
        <input type="hidden" name="custom_front" id="custom_front" value="" />

        <input type="hidden" id="task" name="task" value=""/>
        <input type="hidden" id="current_id" name="current_id" value="<?php echo $row->id; ?>" />
      </form>
      <br/>
      <?php
      foreach($ids as $key => $id) {
        if ($types[$key] != "type_section_break") {
          ?>
          <button onClick="insertAtCursor_form('<?php echo $ids[$key]; ?>','<?php echo $labels[$key]; ?>')" class="fm_label_buttons" title="<?php echo $labels[$key]; ?>"><?php echo $labels[$key]; ?></button>
          <?php
        }
      }
      ?>
      <br /><br />
      <button class="fm_submit_layout button button-secondary button-hero" onclick="autoFormat()"><strong>Apply Source Formatting</strong>  <em>(ctrl-enter)</em></button>
      <textarea id="source" name="source" style="display: none;"></textarea>
    </div>
    <script>
      var editor = CodeMirror.fromTextArea(document.getElementById("source"), {
        lineNumbers: true,
        lineWrapping: true,
        mode: "htmlmixed",
        value: form_front
      });
      if (jQuery('#autogen_layout').is(':checked')) {
        editor.setOption('readOnly',  true);
        editor.setValue(form_front);
      }
      else {
        editor.setOption('readOnly',  false);
        editor.setValue(custom_front);
      }
      jQuery('#autogen_layout').click(function() {
        autogen(jQuery(this).is(':checked'));
      });
      autoFormat();
    </script>
    <?php
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