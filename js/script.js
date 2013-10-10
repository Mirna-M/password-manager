var password_manager = {
    init: function() {
        // This syntax works both for elements already present in DOM and elements
        // yet to be(new elements).
        jQuery('body').on('click', 'a.save', password_manager.onclick_save);
        jQuery('body').on('click', 'a.edit', password_manager.onclick_edit);
        jQuery('body').on('click', 'a.delete', password_manager.onclick_delete);
        
        // We bind function unemployed.onclick_add to click event on a#add
        // (this syntax works only for elements already present in DOM).
        jQuery('a#add').on('click', password_manager.onclick_add);
    },
            
    // This code prevents reloading page when it's clicked on link edit.
    onclick_edit: function(e) {
        e.preventDefault();
  
        // In var manid we put attr data-man-id for row on which edit link is clicked.
        var dataid = jQuery(this).parent().parent().attr('user-data-id');
        
        // When man cliks on edit link we need to hide tr.row and show password_manager.input_row, so
        // for this purpose we use jQuery functions .hide() and .show().
        jQuery('tr.row[user-data-id="'+dataid+'"]').hide();
        jQuery('tr.input_row[user-data-id="'+dataid+'"]').show();
    },
    onclick_save: function(e) {
        e.preventDefault();
        
        // We needed to single out tr element so we could use it easily in later code.
        var tr = jQuery(this).parent().parent();
        
        // We single it out so we could use just manid(variable) instead of longer code 
        // for later coding.
        var dataid = tr.attr('user-data-id');
        
        // Here we found td input.save_name element inside our tr and retreived it's
        // value attribute(string that user wrote inside that input). Before we saved
        // that value we used jQuery.trim() function to eliminate any trailing spaces.
        var user_data_for = jQuery.trim(tr.find('td input.save_user_data_for').val());
        var user_name = jQuery.trim(tr.find('td input.save_user_data_name').val());
        var user_password = jQuery.trim(tr.find('td input.save_user_data_password').val());
        
        // This code is for checking if this variables are empty, if not call function
        // send_save_edit_ajax and give this variables (that we previously defined) as
        // parameters to this function.
        if(user_data_for !='' && user_name!='' && user_password!='') {
            password_manager.send_update_ajax(dataid, user_data_for, user_name, user_password);
        } else {
            alert('All fields need to be entered before saving!');
        }
    },
    send_update_ajax: function(dataid, user_data_for, user_name, user_password) {
        jQuery.ajax({
            url: location.href,
            data: {
                modify: 'save',
                user_data_id: dataid,
                user_data_for: user_data_for,
                user_data_name: user_name,
                user_data_password: user_password
            },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                
                // If function send_save_edit_ajax was successful we need to update regular row 
                // (tr.row). We do that by defining row(tr class row) and it's id specific for 
                // that row which was editing and then saving. Then we find td.save_name and 
                // html which existed before editing replace with data in variable name, 
                // and so on.
                if(data.success == 1) {
                    var tr = jQuery('tr.row[user-data-id="'+dataid+'"]');
                    tr.find('td.save_user_data_for').html(user_data_for);
                    tr.find('td.save_user_data_name').html(user_name);
                    tr.find('td.save_user_data_password').html(user_password);
                    
                    // This is for showing this regular row(tr.row).
                    tr.show();
                    
                    // This code is for hideing input row which was showed in edit and save process.
                    jQuery('tr.input_row[user-data-id="'+dataid+'"]').hide();
                } else {
                    alert('An error occured. Save process unsuccessful.');
                }                
            }
        });
    },
    onclick_delete: function(e) {
        e.preventDefault();
        
        var dataid = jQuery(this).parent().parent().attr('user-data-id');
        password_manager.send_delete_ajax(dataid);
    },
    send_delete_ajax: function(dataid) {
        jQuery.ajax({
            url: location.href,
            data: { modify: 'delete', user_data_id: dataid },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.success == 1) {
                    
                    // jQuey function .remove() removes in this case row on which user clicked for
                    // deletin (we select this specific row with it's id).
                    jQuery('tr[user-data-id="'+dataid+'"]').remove();
                } else {
                    alert('An error occured. Delete process unsuccessful.');
                }
            }
        });
    },
    onclick_add: function(e) {
        e.preventDefault();
        
        var tr = jQuery(this).parent().parent();
        
        var user_data_for = jQuery.trim(tr.find('td input#add_user_data_for').val());
        var user_name = jQuery.trim(tr.find('td input#add_user_data_name').val());
        var user_password = jQuery.trim(tr.find('td input#add_user_data_password').val());
        
        if(user_data_for !='' && user_name!='' && user_password!='') {
            password_manager.send_add_ajax(user_data_for, user_name, user_password);
        } else {
            alert('All fields need to be entered before adding!');
        }
    },
    send_add_ajax: function(user_data_for, user_name, user_password) {
        jQuery.ajax({
            url: location.href,
            data: { 
                modify: 'add',
                user_data_for: user_data_for,
                user_data_name: user_name,
                user_data_password: user_password
            },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.success == 1) {
                    jQuery('table#storage tr:last-child').before(
                        '<tr class="row" user-data-id="'+data.insert_id+'"><td class="save_user_data_for">'+user_data_for+'</td><td class="save_user_data_name">'+user_name+'</td>'+
                        '<td class="save_user_data_password">'+user_password+'</td><td class="modify"><a href="" class="edit">Edit</a> | <a class="delete" href="">Delete</a>'+
                        '</td></tr><tr user-data-id="'+data.insert_id+'" class="input_row"><td><label for="user_data_for">Data for</label><input type="text" value="'+user_data_for+'"'+
                        'name="user_data_for" class="save_user_data_for"></td><td><label for="user_data_name">User name</label><input type="text" value="'+user_name+'" name="user_data_name"'+
                        'class="save_user_data_name"></td><td><label for="user_data_password"></label><input type="text" value="'+user_password+'" name="user_data_password"'+
                        'class="save_user_data_password"></td><td class="modify"><a href="" class="save">Save</a> | <a href="" class="delete">Delete</a></td></tr>');
                    jQuery('table#storage tr#add_row td input').val('');
                } else {
                    alert('An error occured. Add process unsuccessful.');
                }
            }
        });
    }
};

jQuery(document).ready(password_manager.init);