function remove_whitespace(node) {
  var ttt;
  for (ttt = 0; ttt < node.childNodes.length; ttt++) {
    if (node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(node.childNodes[ttt].nodeValue)) {
      node.removeChild(node.childNodes[ttt]);
      ttt--;
    }
    else {
      if (node.childNodes[ttt].childNodes.length) {
        remove_whitespace(node.childNodes[ttt]);
      }
    }
  }
  return;
}

function refresh_() {
  document.getElementById('counter').value = gen;
  if (document.getElementById('form_id_tempform_view1')) {
    document.getElementById('form_id_tempform_view1').removeAttribute('style');
  }
	document.getElementById('form_front').value = document.getElementById('take').innerHTML;
}

function form_maker_options_tabs(id) {
  jQuery("#fieldset_id").val(id);
  jQuery(".fm_fieldset_active").removeClass("fm_fieldset_active").addClass("fm_fieldset_deactive");
  jQuery("#" + id + "_fieldset").removeClass("fm_fieldset_deactive").addClass("fm_fieldset_active");
  jQuery(".fm_fieldset_tab").removeClass("active");
  jQuery("#" + id).addClass("active");
  return false;
}

function set_type(type) {
  switch(type) {
    case 'post':
    document.getElementById('post').removeAttribute('style');
    document.getElementById('page').setAttribute('style','display:none');
    document.getElementById('custom_text').setAttribute('style','display:none');
    document.getElementById('url').setAttribute('style','display:none');
    document.getElementById('none').setAttribute('style','display:none');
    break;
    case 'page':
      document.getElementById('page').removeAttribute('style');
      document.getElementById('post').setAttribute('style','display:none');
      document.getElementById('custom_text').setAttribute('style','display:none');
      document.getElementById('url').setAttribute('style','display:none');
      document.getElementById('none').setAttribute('style','display:none');
      break;
    case 'custom_text':
      document.getElementById('page').setAttribute('style','display:none');
      document.getElementById('post').setAttribute('style','display:none');
      document.getElementById('custom_text').removeAttribute('style');
      document.getElementById('url').setAttribute('style','display:none');
      document.getElementById('none').setAttribute('style','display:none');
      break;
    case 'url':
      document.getElementById('page').setAttribute('style','display:none');
      document.getElementById('post').setAttribute('style','display:none');
      document.getElementById('custom_text').setAttribute('style','display:none');
      document.getElementById('url').removeAttribute('style');
      document.getElementById('none').setAttribute('style','display:none');
      break;
    case 'none':
      document.getElementById('page').setAttribute('style','display:none');
      document.getElementById('post').setAttribute('style','display:none');
      document.getElementById('custom_text').setAttribute('style','display:none');
      document.getElementById('url').setAttribute('style','display:none');
      document.getElementById('none').removeAttribute('style');
      break;
  }
}

function insertAtCursor(myField, myValue) {
  if (myField.style.display == "none") {
    tinyMCE.execCommand('mceInsertContent', false, "%" + myValue + "%");
    return;
  }
  if (document.selection) {
    myField.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
  }
  else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart;
    var endPos = myField.selectionEnd;
    myField.value = myField.value.substring(0, startPos)
      + "%" + myValue + "%"
      + myField.value.substring(endPos, myField.value.length);
  }
  else {
    myField.value += "%" + myValue + "%";
  }
}

function check_isnum(e) {
  var chCode1 = e.which || e.keyCode;
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {
    return false;
  }
  return true;
}

// Check Email.
function spider_check_email(id) {
  if (document.getElementById(id) && jQuery('#' + id).val() != '') {
    var email_array = jQuery('#' + id).val().split(',');
    for (var email_id = 0; email_id < email_array.length; email_id++) {
      var email = email_array[email_id].replace(/^\s+|\s+$/g, '');
      if (email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1) {
        alert(fmc_objectL10n.fmc_not_valid_email_address);
        jQuery('#' + id).css('border', '1px solid #FF0000');
        jQuery('#' + id).focus();
        jQuery('html, body').animate({
          scrollTop:jQuery('#' + id).offset().top - 200
        }, 500);
        return true;
      }
    }
  }
  return false;
}

function spider_edit_ip(id) {
  var ip = jQuery("#ip" + id).html();
  jQuery("#td_ip_" + id).html('<input id="ip' + id + '" class="input_th' + id + '" type="text" onkeypress="return spider_check_isnum(event)" value="' + ip + '" name="ip' + id + '" />');
  jQuery("#td_edit_" + id).html('<a class="button-primary button button-small" onclick="if (spider_check_required(\'ip' + id + '\', \'IP\')) {return false;} spider_set_input_value(\'task\', \'save\'); spider_set_input_value(\'current_id\', ' + id + '); spider_save_ip(' + id + ')">' + fmc_objectL10n.fmc_SaveIP + '</a>');
}

function spider_save_ip(id) {
  var ip = jQuery("#ip" + id).val();
  var post_data = {};
  post_data["ip"] = ip;
  post_data["current_id"] = id;
  post_data["task"] = "save";
  jQuery.post(
    jQuery("#blocked_ips").attr("action"),
    post_data,
    function (data) {
      jQuery("#td_ip_" + id).html('<a id="ip' + id + '" class="pointer" title="' + fmc_objectL10n.fmc_Edit + '" onclick="spider_edit_ip(' + id + ')">' + ip + '</a>');
      jQuery("#td_edit_" + id).html('<a onclick="spider_edit_ip(' + id + ')">' + fmc_objectL10n.fmc_Edit + '</a>');
    }
  ).success(function (data, textStatus, errorThrown) {
    jQuery(".update, .error").hide();
    jQuery("#fm_blocked_ips_message").html("<div class='updated'><strong><p>" + fmc_objectL10n.fmc_Items_succesfully_saved + "</p></strong></div>");
    jQuery("#fm_blocked_ips_message").show();
  });
}

function wdhide(id) {
  document.getElementById(id).style.display = "none";
}

function wdshow(id) {
  document.getElementById(id).style.display = "block";
}

function cfm_create_input(toAdd_id, value_id, parent_id, cfm_url) {
  var value = jQuery("#" + value_id).val();
  if (value) {
    jQuery("#" + value_id).attr("style", "width: 250px;");
    var mail_div = jQuery("<div>").attr("class", "fm_mail_div").prependTo("#" + parent_id).text(value);
    jQuery("<img>").attr("src", cfm_url + "/images/delete.png").attr("class", "fm_delete_img").attr("onclick", "fm_delete_mail(this, '" + value + "')").attr("title", fmc_objectL10n.fmc_Delete_email).appendTo(mail_div);
    jQuery("#" + value_id).val("");
    jQuery("#" + toAdd_id).val(jQuery("#" + toAdd_id).val() + value + ",");
  }
}

function fm_delete_mail(img, value) {
  jQuery(img).parent().remove();
  jQuery("#mail").val(jQuery("#mail").val().replace(value + ',', ''));
}
