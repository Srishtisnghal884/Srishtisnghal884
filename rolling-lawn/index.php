 <?php
/*
* Plugin Name: Rolling Lawn
* Description: Rolling Lawn shortcode.
* Version: 1.0
* Author: InkThemes
* Author URI: https://www.inkthemes.com
*/


function my_enqueued_assets() {
    wp_enqueue_style('my-css-file', plugin_dir_url(__FILE__) . '/assets/css/main.css', '', time());
    //wp_enqueue_style('lr-css-file', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', '', time());
    wp_enqueue_style('lr-css-file', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', '', time());
    wp_enqueue_script('jquery-js-file','https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js','',time());
    wp_enqueue_script('lr-js-file','https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js','',time());
  //  wp_enqueue_script('jquery-js-file','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js','',time());

    wp_enqueue_script('my-js-file', plugin_dir_url(__FILE__) . '/assets/js/main.js', '', '1.0.0', array( 'jquery' ),'',true );
}
add_action('wp_enqueue_scripts', 'my_enqueued_assets');
function html_form_code() {
    echo '<div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form id="msform" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" >
                    <!-- progressbar -->
                    <ul id="progressbar">
                        <li class="active">Liefertemin</li>
                        <li>Dunger</li>
                        <li>Zurr kasse</li>
                    </ul>
                    <!-- fieldsets -->
                    <fieldset class="firstStep">
                        <div class="kostetDiv">
                            <h2 class="fs-title">Was kostet Dein Rollraen?</h2>
                            <input type="text" id="price" name="price" placeholder="Menge in m2" onkeypress="return validateNumber(event)" />
                            <input type="text" id="zip_code" name="zip_code" placeholder="PLZ" maxlength="5" minlength="5" onkeypress="return validateNumber(event)"/>
                             <span id="ValiMengein"></span>
                             <span id="ValiMengein1"></span>
                        </div>
                        <input type="button" id="lr_zip_pr" name="next" class="next action-button"
                            value="Preise & Liefertermine anzeigen >" />

                        <div class="stepPointsText">
                            <ul>
                                <li>Kostenloser Versand bundesweit</li>
                                <li>Zuvertassige Lieferung 2um Wunschtermin</li>
                                <li>immer frisch auf Bestellung geschalt</li>
                            </ul>
                        </div>
                    </fieldset>
                    <fieldset class="secondStep">
                        <div class="kostetDiv">
                            <h2 class="fs-title pro_title"></h2>
                        </div>

                        <div class="universalBox">
                            <div class="imageBox">
                                <img src="https://b3n3h2.myraidbox.de/wp-content/uploads/2022/02/box-image001.jpg" alt="">
                            </div>
                            <div class="boxContent">
                                <h3 class="prodcutname">Universal-Rollrasen</h3>
                                <h4 class="proPrice"></h4>
                                <h5 class="proSubtittle">Kostenlose Lieferung</h5>
                            </div>
                        </div>

                        <div class="kostetDiv">
                            <h2 class="fs-title">Wahle deinen Liefertermin:</h2>
                        </div>

                         
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button up" type="button" id="spnTop">
                                <div id="showLess">frühere Termine anzeigen</div>
                            </button>
                        </h2>
                    
                           <div class="lieferterminRadios loadMoreDates" id="rdio_app">
                           </div>
                        
                         <h2 class="accordion-header" id="headingOne1">
                            <button class="accordion-button" type="button" id="">
                                <div id="loadMore">spätere Termine anzeigen</div>
                            </button>
                        </h2>
                         

                        <div class="loadMoreSec">
                            <div class="infos">
                                <h4>Infos zur Lieferung</h4>
                            </div>
                         </div>



                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        <input type="button" name="next" class="next action-button" id="show_upsell" value="Next" />
                        <div class="stepPointsText">
                            <ul>
                                <li>Kostenloser Versand bundesweit</li>
                                <li>Zuvertassige Lieferung 2um Wunschtermin</li>
                                <li>immer frisch auf Bestellung geschalt</li>
                            </ul>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="kostetDiv">
                            <h2 class="fs-title pro_title"></h2>
                        </div>

                        <div class="universalBox">
                            <div class="imageBox">
                                <img src="https://b3n3h2.myraidbox.de/wp-content/uploads/2022/02/box-image001.jpg" alt="">
                            </div>
                            <div class="boxContent">
                                <h3 class="prodcutname">Universal-Rollrasen</h3>
                                <h4 class="proPrice"></h4>
                                <h5 class="proSubtittle">Kostenlose Lieferung</h5>
                            </div>
                        </div>
                        <div class="lieferterminRadios checkSpecial">
                            <div class="formGroup1">
                                <div class="checkBoxG">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <div class="sell_wrap"></div>
                            </div>
                        </div>
                        <div class="kostetDiv">
                            <h2 class="fs-title">Bestelle den passenden Danger mit:</h2>
                        </div>
                       
                        <div class="dugerMitInner" id="add_cross_cell">
                        </div>

                        <div class="bottomPrice">
                            <h3>Gesamtpreis inkl. Lieferung</h3>
                            <h4 class="total_price">470,00 € </h4>
                        </div>
                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        <input type="submit" name="submit" onclick="submitForm()" class="submit action-button" value="Submit" />
                        <div class="stepPointsText">
                            <ul>
                                <li>Kostenloser Versand bundesweit</li>
                                <li>Zuvertassige Lieferung 2um Wunschtermin</li>
                                <li>immer frisch auf Bestellung geschalt</li>
                            </ul>
                         
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>';
}

add_shortcode( 'rr1-buy-box', 'html_form_code' );

?>
<!-- frühere Termine anzeigen -->
<!-- spätere Termine anzeigen -->