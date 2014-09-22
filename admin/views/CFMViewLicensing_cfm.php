<?php

class CFMViewLicensing_cfm {
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
    ?>
    <div style="width:99%">
      <p>This plugin is the non-commercial version of the Contact Form Builder. The plugin can be used free of charge. There are some limitations with this version of the plugin - no access to Submissions section (only emails sent), as well as Themes. If you want to use those two sections, you are required to purchase a license.</p>
      <br/>
      <a href="http://web-dorado.com/files/fromContactFormBuilder.php" class="button-primary" target="_blank">Purchase a License</a>
      <br/><br/>
      <p>After the purchasing the commercial version follow this steps:</p>
      <ol>
        <li>Deactivate Contact Form Builder Plugin.</li>
        <li>Delete Contact Form Builder Plugin.</li>
        <li>Install the downloaded commercial version of the plugin.</li>
      </ol>
      <br/>
      <p>If you enjoy using Contact Form Builder and find it useful, please consider making a donation. Your donation will help encourage and support the plugin's continued development and better user support.</p>
      <br/>
      <a href="http://web-dorado.com/files/donate_redirect.php" target="_blank"><img src="<?php echo WD_CFM_URL . '/images/btn_donateCC_LG.gif'; ?>" /></a>
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