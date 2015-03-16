<?php

function contact_form_maker_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.18') == -1) {
    $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "contactformmaker` ADD `wpmail` tinyint(4) NOT NULL DEFAULT '1'");
  }
  return;
}

?>