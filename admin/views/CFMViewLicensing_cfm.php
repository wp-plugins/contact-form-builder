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
    <div style="text-align:center; float: left;">
      <table class="data-bordered">
        <thead>
          <tr>
            <th class="top first" nowrap="nowrap" scope="col">Features of the Contact form builder</th>
            <th class="top notranslate" nowrap="nowrap" scope="col">Free</th>
            <th class="top notranslate" nowrap="nowrap" scope="col">Pro Version</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alt">
            <td>Responsive design and layout</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Email Options</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Customizable labels and attributes</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Custom HTML field</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Possibility to send a copy of message to user</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Activation/deactivation of fields</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Customizable form layout</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Captcha/Repatcha protection</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Blocking IPs</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Sample Forms</td>
            <td style="text-align:center;">10</td>
            <td style="text-align:center;">10</td>
          </tr>
          <tr class="alt">
            <td>Possibility to create new forms based on the samples</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Google Map Integration</td>
            <td class="icon-replace yes">yes</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Themes</td>
            <td class="icon-replace yes">1</td>
            <td class="icon-replace yes">37</td>
          </tr>
          <tr class="alt">
            <td>Possibility to add themes</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Submissions page</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr class="alt">
            <td>Statistical Data</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
          <tr>
            <td>Data Import in CSV/XML</td>
            <td class="icon-replace no">no</td>
            <td class="icon-replace yes">yes</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="float: right; text-align: right;">
      <a style="text-decoration: none;" target="_blank" href="http://web-dorado.com/files/fromContactFormBuilder.php">
        <img width="215" border="0" alt="web-dorado.com" src="<?php echo WD_CFM_URL . '/images/wd_logo.png'; ?>" />
      </a>
    </div>
    <div style="float: left; clear: both;">
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