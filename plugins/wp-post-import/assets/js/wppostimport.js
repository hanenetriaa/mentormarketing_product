jQuery(document).ready(function()
{   
    var current_fs, next_fs, previous_fs; //fieldsets

    var left, opacity, scale; //fieldset properties which we will animate

    var animating; //flag to prevent quick multi-click glitches
 
    jQuery(".next").click(function(){

        jQuery('.file_error').hide();
        
        if(jQuery(this).data('id') == 2)
        { 
            if (jQuery('#csvimportk').val() == '') 
            {
                jQuery('.file_error').show();
                return false;
            }
        }  
        
            

        if(animating) return false;

        animating = true;

        

        current_fs = jQuery(this).parent();

        next_fs = jQuery(this).parent().next();

        

        //activate next step on progressbar using the index of next_fs

        jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");

        

        //show the next fieldset

        next_fs.show(); 

        //hide the current fieldset with style

        current_fs.animate({opacity: 0}, {

            step: function(now, mx) {

                scale = 1 - (1 - now) * 0.2;

                //2. bring next_fs from the right(50%)

                left = (now * 50)+"%";

                //3. increase opacity of next_fs to 1 as it moves in

                opacity = 1 - now;

                current_fs.css({

            'transform': 'scale('+scale+')',

            'position': 'absolute'

          });

                next_fs.css({'left': left, 'opacity': opacity});

            }, 

            duration: 800, 

            complete: function(){

                current_fs.hide();

                animating = false;

            }, 

            //this comes from the custom easing plugin

            easing: 'easeInOutBack'

        });

    });



    jQuery(".previous").click(function(){

        if(animating) return false;

        animating = true;

        

        current_fs = jQuery(this).parent();

        previous_fs = jQuery(this).parent().prev();

        

        //de-activate current step on progressbar

        jQuery("#progressbar li").eq(jQuery("fieldset").index(current_fs)).removeClass("active");

        

        //show the previous fieldset

        previous_fs.show(); 

        //hide the current fieldset with style

        current_fs.animate({opacity: 0}, {

            step: function(now, mx) {

                //as the opacity of current_fs reduces to 0 - stored in "now"

                //1. scale previous_fs from 80% to 100%

                scale = 0.8 + (1 - now) * 0.2;

                //2. take current_fs to the right(50%) - from 0%

                left = ((1-now) * 50)+"%";

                //3. increase opacity of previous_fs to 1 as it moves in

                opacity = 1 - now;

                current_fs.css({'left': left});

                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});

            }, 

            duration: 800, 

            complete: function(){

                current_fs.hide();

                animating = false;

            }, 

            //this comes from the custom easing plugin

            easing: 'easeInOutBack'

        });

    });


    jQuery(".submit").click(function(){

        if (jQuery('.post-title').val() == '') 
            {

                jQuery('.post-title').css('border','1px solid red');
                jQuery('.title_error').show();
                return false;
            }
             jQuery('#loader').show();
        //return false;

    });

    jQuery('.get_post_type').on('click', function(){
        jQuery('#custom_field_loder').show();
           
        var update_value_post = jQuery("input[name='optfile']:checked").val();            
         
        jQuery.ajax({

            url: wppijs_ajax_object.ajaxurl,

            type:"post",

            dataType:"json",

            data: ({update_value_post:update_value_post,action:'wppi_show_data', security:wppijs_ajax_object.ajax_nonce}),

            success:function (data) { 
            
            jQuery('#custom_field_loder').hide();
            jQuery('#show_custom').html(data);

            },

        });

    });

    jQuery("#checkfile").on("click", function ()

    {  
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;

        if (regex.test(jQuery("#csvimportk").val().toLowerCase())) {

            if (typeof (FileReader) != "undefined") {

                var reader = new FileReader();

                reader.onload = function (e) {

                    var table = jQuery("<table />");

                    var rows = e.target.result.split("\n"); 

                    for (var i = 0; i < 1; i++) {
                       var ii = i+1;
                        var cells = rows[i].split(",");
                        var cells2 = CSVtoArray(rows[ii]);                          
                       
                        if (cells.length > 1) {
                            for (var j = 0; j < cells.length; j++) {
                                var row = jQuery("<tr />");
                                var cell = jQuery("<td />");
                                var cell2 = jQuery("<td />");
                                cell.html(cells[j]);
						        cell2.html(cells2[j]);						        
                                row.append(cell);
                                row.append(cell2);
                            table.append(row);
                            }
                        }
                    }

                    jQuery(".csvdata").html('');

                    jQuery(".csvdata").html(table);

                }

                reader.readAsText(jQuery("#csvimportk")[0].files[0]);

            } else {

                alert("This browser does not support HTML5.");

            }

        }else {            

            //Validate whether File is valid Excel file.

            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;

            var fileUpload = jQuery("#csvimportk")[0];

            if (regex.test(fileUpload.value.toLowerCase())) {

                if (typeof (FileReader) != "undefined") {

                    var reader = new FileReader();

                    //For Browsers other than IE.

                    if (reader.readAsBinaryString) {

                        reader.onload = function (e) {

                            ProcessExcel(e.target.result);

                        };

                        reader.readAsBinaryString(fileUpload.files[0]);

                    } else {

                        //For IE Browser.

                        reader.onload = function (e) {

                            var data = "";

                            var bytes = new Uint8Array(e.target.result);

                            for (var i = 0; i < bytes.byteLength; i++) {

                                data += String.fromCharCode(bytes[i]);

                            }

                            ProcessExcel(data);
                        };

                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }

                } else {
                    alert("This browser does not support HTML5.");

                }

            } 

        }

    });

});


function ProcessExcel(data) {
    //Read the Excel File data.

    var workbook = XLSX.read(data, {

        type: 'binary'

    });

    //Fetch the name of First Sheet.

    var firstSheet = workbook.SheetNames[0];
    //Read all rows from First Sheet into an JSON array.

    var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]); 

    var table_body = '<table>';       

    jQuery.each(excelRows[0], function(key, value) {
        table_body+='<tr>';

        table_body+='<td>';

        table_body+=key;

        table_body+='</td>';

        table_body+='<td>';

        table_body+=value;

        table_body+='</td>';
        table_body+='</tr>';

    });

    table_body+='</table>';
 
    jQuery('.csvdata').html(table_body); 


}
// Return array of string values, or NULL if CSV string not well formed.
function CSVtoArray(text) {
    var re_valid = /^\s*(?:'[^'\\]*(?:\\[\S\s][^'\\]*)*'|"[^"\\]*(?:\\[\S\s][^"\\]*)*"|[^,'"\s\\]*(?:\s+[^,'"\s\\]+)*)\s*(?:,\s*(?:'[^'\\]*(?:\\[\S\s][^'\\]*)*'|"[^"\\]*(?:\\[\S\s][^"\\]*)*"|[^,'"\s\\]*(?:\s+[^,'"\s\\]+)*)\s*)*$/;
    var re_value = /(?!\s*$)\s*(?:'([^'\\]*(?:\\[\S\s][^'\\]*)*)'|"([^"\\]*(?:\\[\S\s][^"\\]*)*)"|([^,'"\s\\]*(?:\s+[^,'"\s\\]+)*))\s*(?:,|$)/g;
    // Return NULL if input string is not well formed CSV string.
    if (!re_valid.test(text)) return null;
    var a = [];                     // Initialize array to receive values.
    text.replace(re_value, // "Walk" the string using replace with callback.
        function(m0, m1, m2, m3) {
            // Remove backslash from \' in single quoted values.
            if      (m1 !== undefined) a.push(m1.replace(/\\'/g, "'"));
            // Remove backslash from \" in double quoted values.
            else if (m2 !== undefined) a.push(m2.replace(/\\"/g, '"'));
            else if (m3 !== undefined) a.push(m3);
            return ''; // Return empty string.
        });
    // Handle special case of empty last value.
    if (/,\s*$/.test(text)) a.push('');
    return a;
};