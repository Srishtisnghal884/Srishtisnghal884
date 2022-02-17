
    $("#lr_zip_pr").click(function(e) {
        e.preventDefault();
           var zip_code= $("#zip_code").val();
           var price= $("#price").val();
           if(zip_code != "" && price != ""){
              $('.pro_title').html('');
               $('.pro_title').append(price +' qm Rolirasen nach '+zip_code);
               jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/rolllawn-price/zip_code='+zip_code+'&price='+price, function(json_datas){
                    if(json_datas)
                    {
                       $('.proPrice').append(json_datas.par_sq_m);
                       // localStorage['rollen_price_cal'] = json_datas.price;
                       sessionStorage.setItem("rollen_price_cal", json_datas.price);
                    }
               });
               jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/rolllawn/zip_code='+zip_code+'&price='+price, function(json_data){
                if(json_data)
                {
                    // console.log(json_data);
                    var dateCount = 0;
                    var scarcityCounter = 0;
                     $('#rdio_app').html('');
                    for (i=0; i<json_data.length; i++){
                        dateCount++;
                        var dateCountClass = (dateCount > 5) ? 'fc-date-hidden ' : '';
                        var dateInfoClass = '';
                        var dateInfo = '';
                        var lineth = '';
                        var dt = new Date(json_data[i].date);
                        var d = dt.getDay()
                        if (dateCount == 1) {
                            dateInfo = 'ausgebucht';
                            dateInfoClass = 'fc-date-full';
                            lineth = 'slables';
                          }
                        else if ( (d == 2 || d == 5) && scarcityCounter < 3) {
                            scarcityCounter++;
                            dateInfo = 'wenige Plätze!';
                            dateInfoClass = 'winzePrice';
                        }
                        else
                        {
                            dateInfo = '';
                        }
                         var htm = '';
                         var mydate = new Date(json_data[i].date);
                         var str = mydate.toDateString();
                                                  
                          htm += "<div class='formGroup "+dateInfoClass+" '><div class='radioLeft'><input type='radio' id='huey' name='datefullrl' data-id='"+dateInfo+"'  value='"+str+"' class='getvalrl'><label for='huey'>diese Woche</label><label for='huey' class='secondLable "+lineth+"'>"+str+"</label></div><div class='radioRight'><span>"+dateInfo+"</span></div></div>";
                         
                         $('#rdio_app').append(htm);
                         $('.fc-date-full .getvalrl').attr("disabled", true);
                     // console.log(htm);
                     }
                        $('.getvalrl').click(function(){
                            var dates = $("input[name=datefullrl]:checked").val();
                            var info = $(this).attr("data-id");
                            // alert(dates+info);
                            $('.sell_wrap').html('');
                            $('.sell_wrap').append('<div class="radioLeft"><label for="huey">diese Woche</label><label for="huey" class="secondLable">'+dates+'</label></div><div class="radioRight"><span>'+info+'</span></div>');
                         });

                       $(document).ready(function() {
                             // setInterval(function () {
                                 size = $('.formGroup').length;
                                  x = 5;
                                  $('.formGroup:lt(' + x + ')').show();
                                  $('#loadMore').click(function() {
                                    if (x + 5 > size) return;
                                    $('.formGroup').hide();
                                    $('.formGroup').slice(x, x + 5).show();
                                    x += 5;
                                  });
                                  $('#showLess').click(function() {
                                    if (x - 5 <= 0) return;
                                    $('.formGroup').hide();
                                    x -= 5;
                                    $('.formGroup').slice(x - 5, x).show();
                                  });
                                 // }, 1000); 
                        });
                       // 
                    }
                    else
                    {
                        $('#rdio_app').append('Für die angegebene Menge und PLZ konnte leider kein Preis online berechnet werden. Bitte schreiben Sie uns eine E-Mail und wir werden Ihren Preis berechnen.');
                    }
                   

               });
           }
    });

  $("#show_upsell").click(function() {
           var zip_code= $("#zip_code").val();
           var price= $("#price").val();
            if(zip_code != "" && price != ""){
               $('#add_cross_cell').html('');
         
               jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/rolllawn-sell-product/zip_code='+zip_code+'&price='+price, function(json_data){
                   // alert(json_data);
                    if(json_data)
                    {  
                     console.log(json_data);
                            $.each(json_data, function(i, item) {
                                var vari = '';
                                var idcart = '';
                                if(item.variation){
                                   vari = '<p class="greenText">'+item.variation+'</p>';  
                                   idcart = 'fc-add-to-cart'; 
                                }
                                else
                                {
                                   vari = '';   
                                   idcart = 'fc-add-to-cart-Preküh'; 
                                }
                                 $('#add_cross_cell').append('<div class="form_Groups" ><div class="dugerMitleft '+idcart+'" data-id="'+item.product_id+'" data-quantity="1" data-price="'+item.display_price+'" data-varientid="'+item.varient_id+'"><span class="change_icon_cls_'+item.product_id+'"><i class="fa fa-plus" aria-hidden="true"></i></span></div><div class="dugerMitRight"><div class="HeaderTop"><h3>'+item.title+'</h3><a href="#">emphoheim</a></div><div class="clearAll"></div><h4>'+item.price+'</span></h4>'+vari+'<p>'+item.description+'</p></div></div>');
                            });
                        }
                });    
           }
  });
  
  // $(".fc-add-to-cart").click(function() {
  //   var product_id = $(this).attr("data-id");
  //   var price= $("#price").val();
  //   if(product_id != '' && price != "")
  //   {
  //      jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/wc-nonce', function(json_data){
  //       if(json_data)
  //       {
  //          if( $('.change_icon_cls_'+product_id).hasClass("intro"))
  //          {
  //            $('.change_icon_cls_'+product_id).removeClass('add-item-cart');
  //            $('.change_icon_cls_'+product_id).html('<i class="fa fa-plus" aria-hidden="true"></i>');
  //          }
  //          else
  //          {
  //           $('.change_icon_cls_'+product_id).addClass('add-item-cart');
  //           $('.add-item-cart').html('<i class="fa fa-check" aria-hidden="true"></i>');
  //           jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/rolllawn_get_product_price/product_id='+product_id+'&price='+price, function(json_datas){
  //                   if(json_datas)
  //                   {   
  //                           var nonce = json_data;
  //                           $.ajax({
  //                             type: 'POST',
  //                             url: 'https://b3n3h2.myraidbox.de/wp-json/wc/store/cart/add-item',
  //                             dataType: 'json',
  //                             crossDomain: true,
  //                             headers: {
  //                                "accept": "application/json"
  //                             },
  //                             data: {
  //                               id : json_datas.product_ids,
  //                               quantity: '1',
  //                               'nonce': nonce
  //                             },
  //                              success: function(data) {
                                  
  //                               // Ajax call completed successfully
  //                                   alert("Form Submited Successfully");
  //                               },
  //                               error: function(data) {
  //                                     console.log(data);
  //                                   // Some error in ajax call
  //                                   alert("some Error");
  //                               }
  //                           });
  //                   }
  //           });
  //          }

  //       }
  //     });
  //   }
  // });

     function submitForm() {
        var product_id = '244738';
        var price = '1050';
             jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/rolllawn_add_product_cart/product_id='+product_id+'&price='+price, function(json_datas){
                if(json_datas)
                {   
                    console.log(json_datas);
                }    
            // let data = sessionStorage.getItem('rollen_price_cal');
            // console.log('vivek',data);
           
            // jQuery.getJSON('https://b3n3h2.myraidbox.de/wp-json/v1/wc-nonce', function(json_data){
            //     if(json_data)
            //     {
            //         var nonce = json_data;
            //         console.log(nonce);

            //         $.ajax({
            //           type: 'POST',
            //           url: 'https://b3n3h2.myraidbox.de/wp-json/wc/store/cart/add-item',
            //           dataType: 'json',
            //           crossDomain: true,
            //           headers: {
            //              "accept": "application/json"
            //           },
            //           data: {
            //             id : '244738',
            //             quantity: '1',
            //             'nonce': nonce
            //           },
            //            success: function(data) {
                          
            //             // Ajax call completed successfully
            //                 alert("Form Submited Successfully");
            //             },
            //             error: function(data) {
            //                   console.log(data);
            //                 // Some error in ajax call
            //                 alert("some Error");
            //             }
            //         });
            //     }
            // });
            });

        }
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches


        function validateNumber(e) {
            const pattern = /^[0-9]$/;
            return pattern.test(e.key )
        }

        function validation(current, next) {
            // console.log("validation");
            var zip_code= $("#zip_code").val();
            var price= $("#price").val();

            var focusSet = false;
             if(zip_code == '') 
             {
                   document.getElementById('ValiMengein1').innerHTML = 'Die PLZ muss 5-stellig sein';
             }
             else
             {
                    document.getElementById('ValiMengein1').innerHTML = '';
                    // if(zip_code.length == 5)
                    // {
                    //      document.getElementById('ValiMengein1').innerHTML = '';
                    //      focusSet = true;
                    // }
                    // else
                    // {
                    //       document.getElementById('ValiMengein1').innerHTML = 'Die PLZ muss 5-stellig sein';
                    //       focusSet = false;
                    // }
             }
             if(price == '')
             {

                 document.getElementById('ValiMengein').innerHTML = 'Die Mindestbestellmenge liegt bei 30m²';
             }
             else
             {
                   document.getElementById('ValiMengein').innerHTML = '';
                   if(price > 30){
                         document.getElementById('ValiMengein').innerHTML = '';
                         focusSet = true;
                    }
                    else
                    {
                           document.getElementById('ValiMengein').innerHTML = 'Die Mindestbestellmenge liegt bei 30m²';
                          focusSet = false;
                    }
             }
            if(focusSet == true)
            {
                 nextFunction(current, next);
            }
        }




        function validation1(current, next) {
            //var plzfeild = document.getElementById("plzfeild").value;
            var atLeastOneChecked = false;
            $("input[type=radio][name=datefullrl]").each(function () {
                //console.log($(this).attr("checked"));

                if ($(this).is(':checked')) {
                    atLeastOneChecked = true;
                }
            });
            // console.log("validation1", atLeastOneChecked);

            if (!atLeastOneChecked) {
                alert('Bitte wähle einen Liefertermin, um fortzufahren');
                $("#secondNext").attr("disabled", true);
                return false

            } else {
                $("#secondNext").attr("disabled", false);
                nextFunction(current, next)
            }
        }

     
        function nextFunction(currentVal, nextVal) {
            if (animating) return false;
            animating = true;
            current_fs = currentVal;
            next_fs = nextVal;
            //activate next step on progressbar using the index of next_fs
            let test = $("#progressbar li").eq($("fieldset").index(next_fs))
            // console.log("testtttttttttttttttt", test);
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        }


        $(".next").click(function () {
            // console.log("next called");
            let current = $(this).parent();
            let next = $(this).parent().next();
            let stepName = $(current[0]).attr('class')
            // console.log("stepName", stepName);
            if (stepName == "firstStep") {
                validation(current, next)
            } else if (stepName == "secondStep") {
                // console.log("secondStep validation", validation1());
                // validation1() && nextFunction(current, next)
                validation1(current, next)
            }

        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();
            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function () {
            return false;
        })

