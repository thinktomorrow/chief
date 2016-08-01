'use strict';
/*! docs.js - v0.1.1
 * http://admindesigns.com/
 * Copyright (c) 2015 Admin Designs;*/

/* Core Documentation functions required for
 * most of the themes vital functionality */
var Docs = function(options) {

   // Variables
   var Body = $('body');


   // Form related Functions
   var runCore= function(options) {

        var Body = $('body');







      // /*!
      // *** prettyPre ***/

      // (function( $ ) {

      //     $.fn.prettyPre = function( method ) {

      //         var defaults = {
      //             ignoreExpression: /\s/ // what should be ignored?
      //         };

      //         var methods = {
      //             init: function( options ) {
      //                 this.each( function() {
      //                     var context = $.extend( {}, defaults, options );
      //                     var $obj = $( this );
      //                     var usingInnerText = true;
      //                     var text = $obj.get( 0 ).innerText;

      //                     // some browsers support innerText...some don't...some ONLY work with innerText.
      //                     if ( typeof text == "undefined" ) {
      //                         text = $obj.html();
      //                         usingInnerText = false;
      //                     }

      //                     // use the first line as a baseline for how many unwanted leading whitespace characters are present
      //                     var superfluousSpaceCount = 0;
      //                     var currentChar = text.substring( 0, 1 );

      //                     while ( context.ignoreExpression.test( currentChar ) ) {
      //                         currentChar = text.substring( ++superfluousSpaceCount, superfluousSpaceCount + 1 );
      //                     }

      //                     // split
      //                     var parts = text.split( " \n" );
      //                     var reformattedText = "";

      //                     // reconstruct
      //                     var length = parts.length;
      //                     for ( var i = 0; i < length; i++ ) {
      //                         // cleanup, and don't append a trailing newline if we are on the last line
      //                         reformattedText += parts[i].substring( superfluousSpaceCount ) + ( i == length - 1 ? "" : " \n" );
      //                     }

      //                     // modify original
      //                     if ( usingInnerText ) {
      //                         $obj.get( 0 ).innerText = reformattedText;
      //                     }
      //                     else {
      //                         // This does not appear to execute code in any browser but the onus is on the developer to not 
      //                         // put raw input from a user anywhere on a page, even if it doesn't execute!
      //                         $obj.html( reformattedText );
      //                     }
      //                 } );
      //             }
      //         }

      //         if ( methods[method] ) {
      //             return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ) );
      //         }
      //         else if ( typeof method === "object" || !method ) {
      //             return methods.init.apply( this, arguments );
      //         }
      //         else {
      //             $.error( "Method " + method + " does not exist on jQuery.prettyPre." );
      //         }
      //     }
      // } )( jQuery );
      //   $('code').prettyPre();


        // Hover function for expanding large highlight trays
        // that are marked with the "highlight-hover" class
        function highlightCheck() {

          if ($('html.template-page').length) {return;}
          
          $('div.highlight').each(function(i,e) {

              var This = $(this);
              if (This.hasClass('force-height')) { return;}

              var Selector = This.children('pre');
              if (Selector.height() > 185) {
                This.addClass('highlight-hover');
                
                // var Selector = This.children('pre');
                var autoHeight = Selector.css('height', 'auto').height();
                                 Selector.css('height', '175');

                This.hoverIntent({
                  over: function() { Selector.css('height', autoHeight + 45); },
                  out: function() { Selector.css('height', ''); },
                  timeout: 300
                });

              } 

          });
         }
         highlightCheck();

        // Trigger highlight check on tab change.
        $('li.list-group-item a[data-toggle]').on('click', function() {
            setTimeout(function() {
              highlightCheck();
            },200);
        });

        // Prevents directory response when submitting a demo form
        $('.admin-form').on('submit', function(e) {

           if($('body.timeline-page').length || $('body.admin-validation-page').length) {
              return;
           }
           e.preventDefault;
           alert('Your form has submitted!');
           return false;
        });

        // Slide content functionality for template pages
        if ($('html').hasClass('template-page')) {
          $('#template-code').on('click',function() {
             Body.addClass('offscreen-active');
          });
          $('#template-return').on('click',function() {
             Body.removeClass('offscreen-active');
          });
        }

        // Init Bootstrap Popovers, if present 
        if ($("[data-toggle=popover]").length) {
            $('[data-toggle=popover]').popover();
        }

        // Clear popovers after timeout
        var timer;
        $('[data-toggle=popover]').on('click', function(e) {
            clearTimeout(timer);
            timer = setTimeout(function() {
              $('[data-toggle=popover]').popover('hide');
            },3000)      
        });

        // Init Footable Plugin, if present 
        if ($('table.footable').length) {
            $('table.footable').footable();
        }


        // Configure highlight.js plugin
        hljs.configure({
            tabReplace: '  ', // 4 spaces
        });
        // Init Highlight.js plugin
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });

        // Toggle left sidebar functionality
        var toggleInput = $('#left-col-toggle');
        toggleInput.on('click', function() {
            if ($('body.left-col-hidden').length) {
                $('body').removeClass('left-col-hidden');
            } else {
                $('body').addClass('left-col-hidden');
            }
        });

        // list-group-accordion functionality
        var listAccordion = $('.list-group-accordion');
        var accordionItems = listAccordion.find('.list-group-item');
        var accordionLink = listAccordion.find('.sign-toggle');

        accordionLink.on('click', function() {
            var This = $(this);
            var Parent = This.parent('.list-group-item');

            if (Parent.hasClass('active')) {
                Parent.toggleClass('active');
            } else {
                accordionItems.removeClass('active');
                Parent.addClass('active');
            }
        });

        // Mobile catch for hiding the left sidebar
        if ($(window).width() < 940) {
            $('body').addClass('left-col-hidden');
        } else {
            $('body').removeClass('left-col-hidden');
        }

        // Helper function to highlight code text
        jQuery.fn.selectText = function() {
            var doc = document,
                element = this[0],
                range, selection;
            if (doc.body.createTextRange) {
                range = document.body.createTextRange();
                range.moveToElementText(element);
                range.select();
            } else if (window.getSelection) {
                selection = window.getSelection();
                range = document.createRange();
                range.selectNodeContents(element);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        };

        // Highlight code text on click     
        $('.btn-clipboard').on('click', function() {
            var selection2 = $(this).parent('.zero-clipboard').next('.highlight').find('code');
            selection2.selectText();
            // $('#'+selection).selectText();
        });

          var scrollBtn = $('.scrollup');
          // on scoll toggle scrollTop in/out
          $(window).scroll(function () {
              if ($('body').hasClass('scrolling')) {return;}
              if ($(this).scrollTop() > 300) {
                  scrollBtn.fadeIn();
              } else {
                  scrollBtn.fadeOut();
              }
          });
          // on button click scrollTop
          $('.scrollup, .return-top').on('click', function (e) {
              e.preventDefault();
              scrollReset();
          });
          // if link item clicked scrollTop 
          $('#nav-spy li a').on('click', function () {
              if ($(this).hasClass('sign-toggle')) { return; }
              if ($(window).scrollTop() > 170) {
                  scrollReset();
              } 
          });
          // scrollTop function
          function scrollReset() {
              scrollBtn.fadeOut();
              $("html, body").addClass('scrolling').animate({
                  scrollTop: 0
              }, 320, function(){
                 $("html, body").removeClass('scrolling')
              });
              return false;
          }
      
   }

   // Init AdminForm SLIDER Widgets
   // ---------------------------------
   var runSlider= function(options) {

        if (!$('#slider-single').length) {return;}

        // Slider Single
        $("#slider-single").slider({
            range: "min",
            min: 0,
            max: 100,
            value: 40,
        });

        // Slider Double
        $("#slider-double").slider({
            range: true,
            values: [23, 68]
        });

        // Slider with Counter
        $("#slider-count").slider({
            range: "min",
            min: 0,
            max: 100,
            value: 40,
            slide: function( event, ui ) {
               $( "#amount" ).val( "$" + ui.value );
            }
        });

   }

   // Init AdminForm TIMEPICKER Widgets
   // ---------------------------------
   var runColorpicker= function(options) {

        if (!$('#colorpicker-basic').length) {return;}

        // Colorpicker Basic
        var cpBasic = $("#colorpicker-basic");
        cpBasic.spectrum({
            color: '#4a89dc', // set default color
            appendTo: cpBasic.parents('.admin-form'), // append popup to parent container
            containerClassName: 'sp-left' // add helper class to align popup left of addon
        });
        cpBasic.show();

        // Colorpicker Advanced
        var cpAdvanced = $("#colorpicker-advanced");
        cpAdvanced.spectrum({
            color: '#4a89dc', // set default color
            appendTo: cpAdvanced.parents('.admin-form'), // append popup to parent container
            containerClassName: 'sp-left', // add helper class to align popup left of addon
            showInput: true,
            showPalette: true, // show advanced color pallete
            palette: [ // set displayed pallete colors
                [bgPrimary, bgSuccess, bgInfo],
                [bgWarning, bgDanger, bgAlert],
                [bgSystem, bgDark, bgBlack]
            ]
        });
        cpAdvanced.show();

        // Colorpicker Inline
        var cpInline = $(".inline-cp");
        cpInline.spectrum({
            color: '#4a89dc', // set default color
            showInput: true, // show advanced color input
            showPalette: true, // show advanced color pallete
            chooseText: "Select Color", // set text
            flat: true, 
            palette: [ // set displayed pallete colors
                [bgPrimary, bgSuccess, bgInfo, bgWarning,
                    bgDanger, bgAlert, bgSystem, bgDark,
                    bgSystem, bgDark, bgBlack
                ]
            ]
        });
        cpInline.show();

   }


   // Init AdminForm TIMEPICKER Widgets
   // ---------------------------------
   var runTimepicker= function(options) {

        if (!$('#timepicker-basic').length) {return;}

        // Timepicker Basic
        $('#timepicker-basic').timepicker({
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

      // Timepicker Addon
      $('#timepicker-addon').timepicker({
          showOn: 'both',
          buttonText: '<i class="fa fa-clock-o"></i>',
          beforeShow: function(input, inst) {
              var themeClass = $(this).parents('.admin-form').attr('class');
              var smartpikr = inst.dpDiv.parent();
              if (!smartpikr.hasClass(themeClass)) {
                  inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
              }
          }
      });

      // Timepicker Inline
      $('#timepicker-inline').timepicker();
      
   }


   // Init AdminForm TIMEDATEPICKER Widgets
   // ---------------------------------
   var runDateTimepicker= function(options) {

        if (!$('#datetimepicker-basic').length) {return;}

        // DateTimepicker Basic
        $('#datetimepicker-basic').datetimepicker({
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // DateTimepicker Addon
        $('#datetimepicker-addon').datetimepicker({
            showOn: 'both',
            buttonText: '<i class="fa fa-calendar-o"></i>',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // DateTimepicker Inline
        $('#datetimepicker-inline').datetimepicker({
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        });

   }


   // Init AdminForm MONTHPICKER Widgets
   // ---------------------------------
   var runMonthpicker= function(options) {

        if (!$('#monthpicker-basic').length) {return;}

        // Monthpicker Basic
        $('#monthpicker-basic').monthpicker({
            changeYear: false,
            stepYears: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            showButtonPanel: true,
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // Monthpicker Addon
        $("#monthpicker-addon").monthpicker({
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            showOn: 'both',
            buttonText: '<i class="fa fa-calendar-o"></i>',
            showButtonPanel: true,
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // Monthpicker Inline
        $('#monthpicker-inline').monthpicker({
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            showButtonPanel: true
        });

   }


   // Init AdminForm DATEPICKER Widgets
   // ---------------------------------
   var runDatepicker= function(options) {

        if (!$('#datepicker-basic').length) {return;}

        // Basic Datepicker
        $("#datepicker-basic").datepicker({
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // Datepicker addon field
        $('#datepicker-addon').datepicker({
            showOn: 'both',
            buttonText: '<i class="fa fa-calendar-o"></i>',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            beforeShow: function(input, inst) {
                var themeClass = $(this).parents('.admin-form').attr('class');
                var smartpikr = inst.dpDiv.parent();
                if (!smartpikr.hasClass(themeClass)) {
                    inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
                }
            }
        });

        // Datepicker Inline
        $('#datepicker-inline').datepicker({
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        });

        // Datepicker Range
        $("#datepicker-from").datepicker({
          defaultDate: "+1w",
          numberOfMonths: 3,
          prevText: '<i class="fa fa-chevron-left"></i>',
          nextText: '<i class="fa fa-chevron-right"></i>',
          beforeShow: function(input, inst) {
            var themeClass = $(this).parents('.admin-form').attr('class');
            var smartpikr = inst.dpDiv.parent();
            if (!smartpikr.hasClass(themeClass)) {
                inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
            }
          },
          onClose: function( selectedDate ) {
            $("#datepicker-to").datepicker( "option", "minDate", selectedDate );
          }
        });
        $("#datepicker-to").datepicker({
          defaultDate: "+1w",
          numberOfMonths: 3,
          prevText: '<i class="fa fa-chevron-left"></i>',
          nextText: '<i class="fa fa-chevron-right"></i>',
          beforeShow: function(input, inst) {
            var themeClass = $(this).parents('.admin-form').attr('class');
            var smartpikr = inst.dpDiv.parent();
            if (!smartpikr.hasClass(themeClass)) {
                inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
            }
          },
          onClose: function( selectedDate ) {
            $("#datepicker-from").datepicker( "option", "maxDate", selectedDate );
          }
        });



   }



   return {
      init: function(options) {

         // Set Default Options
         var defaults = {
            option1: 'value', // desc
         };

         // Extend Default Options.
         var options = $.extend({}, defaults, options);

         // Call Core Functions
         runCore(options);


         runDatepicker();
         runTimepicker();
         runDateTimepicker();
         runMonthpicker();
         runColorpicker();
         runSlider();

      }

   }
}();

// Global Library of Theme colors for Javascript plug and play use  
var bgPrimary = '#4a89dc',
   bgPrimaryL = '#5d9cec',
   bgPrimaryLr = '#83aee7',
   bgPrimaryD = '#2e76d6',
   bgPrimaryDr = '#2567bd',
   bgSuccess = '#70ca63',
   bgSuccessL = '#87d37c',
   bgSuccessLr = '#9edc95',
   bgSuccessD = '#58c249',
   bgSuccessDr = '#49ae3b',
   bgInfo = '#3bafda',
   bgInfoL = '#4fc1e9',
   bgInfoLr = '#74c6e5',
   bgInfoD = '#27a0cc',
   bgInfoDr = '#2189b0',
   bgWarning = '#f6bb42',
   bgWarningL = '#ffce54',
   bgWarningLr = '#f9d283',
   bgWarningD = '#f4af22',
   bgWarningDr = '#d9950a',
   bgDanger = '#e9573f',
   bgDangerL = '#fc6e51',
   bgDangerLr = '#f08c7c',
   bgDangerD = '#e63c21',
   bgDangerDr = '#cd3117',
   bgAlert = '#967adc',
   bgAlertL = '#ac92ec',
   bgAlertLr = '#c0b0ea',
   bgAlertD = '#815fd5',
   bgAlertDr = '#6c44ce',
   bgSystem = '#37bc9b',
   bgSystemL = '#48cfad',
   bgSystemLr = '#65d2b7',
   bgSystemD = '#2fa285',
   bgSystemDr = '#288770',
   bgLight = '#f3f6f7',
   bgLightL = '#fdfefe',
   bgLightLr = '#ffffff',
   bgLightD = '#e9eef0',
   bgLightDr = '#dfe6e9',
   bgDark = '#3b3f4f',
   bgDarkL = '#424759',
   bgDarkLr = '#51566c',
   bgDarkD = '#2c2f3c',
   bgDarkDr = '#1e2028',
   bgBlack = '#283946',
   bgBlackL = '#2e4251',
   bgBlackLr = '#354a5b',
   bgBlackD = '#1c2730',
   bgBlackDr = '#0f161b';

