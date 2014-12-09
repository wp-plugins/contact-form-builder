<?php

class CFMViewUninstall_cfm {
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
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <form method="post" action="admin.php?page=uninstall_cfm" style="width:95%;">
      <?php wp_nonce_field('contact_form_maker uninstall');?>
      <div class="wrap">
        <span class="uninstall_icon"></span>
        <h2>Uninstall Contact Form Builder</h2>
        <p>
          Deactivating Contact Form Builder plugin does not remove any data that may have been created, such as the Forms and the Submissions. To completely remove this plugin, you can uninstall it here.
        </p>
        <p style="color: red;">
          <strong>WARNING:</strong>
          Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
        </p>
        <p style="color: red">
          <strong>The following WordPress Options/Tables will be DELETED:</strong>
        </p>
        <table class="widefat">
          <thead>
            <tr>
              <th>Database Tables</th>
            </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                <li><?php echo $prefix; ?>contactformmaker</li>
                <li><?php echo $prefix; ?>contactformmaker_submits</li>
                <li><?php echo $prefix; ?>contactformmaker_themes</li>
                <li><?php echo $prefix; ?>contactformmaker_views</li>
                <li><?php echo $prefix; ?>contactformmaker_blocked</li>
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;">
          Do you really want to uninstall Conatct Form Builder?
        </p>
        <p style="text-align: center;">
          <input type="checkbox" name="Contact Form Builder" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
        </p>
        <p style="text-align: center;">
          <input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) { 
                                                                                    if (confirm('You are About to Uninstall Contact Form Builder from WordPress.\nThis Action Is Not Reversible.')) {
                                                                                        spider_set_input_value('task', 'uninstall');
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" />
        </p>
      </div>
      <input id="task" name="task" type="hidden" value="" />
    </form>
    <?php
  }

  public function uninstall() {
    $this->model->delete_db_tables();
    global $wpdb;
    $prefix = $wpdb->prefix;
    $deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=contact-form-builder/contact-form-builder.php', 'deactivate-plugin_contact-form-builder/contact-form-builder.php');
    ?>
    <div id="message" class="updated fade">
      <p>The following Database Tables succesfully deleted:</p>
      <p><?php echo $prefix; ?>contactformmaker,</p>
      <p><?php echo $prefix; ?>contactformmaker_submits,</p>
      <p><?php echo $prefix; ?>contactformmaker_themes,</p>
      <p><?php echo $prefix; ?>contactformmaker_views,</p>
      <p><?php echo $prefix; ?>contactformmaker_blocked.</p>
    </div>
    <div class="wrap">
      <h2>Uninstall Conact Form Builder</h2>
      <p><strong><a href="<?php echo $deactivate_url; ?>">Click Here</a> To Finish the Uninstallation and Contact Form Builder will be Deactivated Automatically.</strong></p>
      <input id="task" name="task" type="hidden" value="" />
    </div>
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