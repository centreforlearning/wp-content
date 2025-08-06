<?php
$fullwidth = ot_get_option('coll_header_fullwidth');
$fullwidth = ($fullwidth) ? '' : 'row';

$logo = ot_get_option('coll_footer_logo');
$logohtml = '';
if (!empty($logo)) {
    $logohtml .= '<div class="logo">';
    $logohtml .= '<a class="no-border" href="' . home_url() . '" >';
    $logohtml .= '<img src="' . $logo . '" alt="' . get_bloginfo('name') . '" />';
    $logohtml .= '</a>';
    $logohtml .= '<p>' . get_bloginfo('description') . '</p>';
    $logohtml .= '</div>';
}

$text = ot_get_option('coll_footer_text');
?>

<?php ?>

<footer class="site-footer">
    <div class="background"></div>
    <div class="coll-footer-wrapper <?php echo $fullwidth; ?>">
        <div class="large-4 medium-4 columns footer-container">
        	<h2><a class="name-style" href="https://cfl.in" title="CFL">Centre For Learning</a></h2>
			<a class="button3" href="https://cfl.in/contact/">Contact Us</a><br />
			<a class="button3" href="https://cfl.in/support-us/how-can-i-help-cfl/">Support Us</a><br/>
			<a target="_blank" href="https://www.instagram.com/theworldwithincfl/"><i class="fa fa-instagram fa-2x" style="color:#018788"></i></a>
			<a target="_blank" href="https://youtube.com/@centreforlearningbangalore6620"><i class="fa fa-youtube-play fa-2x" style="color:#018788"></i></a><br/>
			<p class="copyright-details">&copy; 2016 <span>CFL</span>. All rights reserved.</p>
        </div>
        <div class="large-4 medium-4 columns footer-container">
            <h2>Mailing Address</h2>
			<div class="mailing">
				2, Good Earth Enclave<br />
				Uttarhalli Road, Kengeri<br />
				Bangalore 560060<br />
				India<br />
				<h2>Campus Address</h2>
				Village Varadenahalli<br />
				Magadi Taluka, District Ramanagara<br />
				Varadenahalli-Mahadevapura Road<br />
				Karnataka 562120<br />
				India<br />
				<i class="fa fa-envelope"></i> info@centreforlearning.in  <br />
				<i class="fa fa-phone"> </i> +91-96320 52142 (10AM-2PM M-F)<br />
			</div>
        </div>
        <div class="large-4 medium-4 columns footer-container">
        	<div id="back-to-top">
				
			<h2 class="privacy"><a class="privacy-policy" href="https://cfl.in/terms-of-use-and-privacy-policy/">Terms of Use and Privacy Policy</a></h2>
				<a class="button3 js-coll-local-link" href="#top">Back to top</a><br />
			</div><!-- end #back-to-top -->
        </div>
    </div>
</footer>
</div>  <!-- end main-->
<!-- scroll bar-->
<div class="js-coll-scrollbar">
    <div class="js-coll-scrollbar-content">

    </div>
</div>
<!-- prelaoder -->
<?php
$preloader = ot_get_option('coll_preloader');
if (!empty($preloader)) {
    ?>

    <div class="coll-site-preloader">
        <div class="coll-preloader-container">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>

<?php }; ?>
<?php wp_footer(); ?>
</body>
</html>
