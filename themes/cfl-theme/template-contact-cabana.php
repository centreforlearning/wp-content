<?php
/**
 * Template Name: Contact
 * Description: The template for displaying the contact page.
 *
 *
 * @package cabana
 */
get_header( 'inner' ); ?>

	<main>
	
		<section class="content">
			
			<div class="container">
			
				<div class="nine columns alpha">
				
					<h1><?php the_title(); ?></h1>
							
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								
						<?php the_content(); ?>
						
					<?php endwhile; endif; ?>
					
					<form class="contact-form-standard" action="<?php echo get_template_directory_uri(); ?>/form/form.php" method="post">
										
						<input name="name" type="text" placeholder="<?php _e( 'Your Name (required)', 'cabana' ); ?>">
		
					    <input name="email" type="email" placeholder="<?php _e( 'Your Email (required)', 'cabana' ); ?>">
		
					    <textarea name="message" placeholder="<?php _e( 'Please enter your message...', 'cabana' ); ?>"></textarea>
					    
					    <p id="user">
					    <input name="username" type="text" placeholder="Your Username">
					    </p>
					            
					    <input id="submit" name="submit" type="submit" value="Submit">
					        
					</form><!-- end .contact-form-standard -->
					
					<div id="response"></div>
					
				</div>
				
				<div class="six columns offset-by-one omega">
				
					<div class="contact-info">
						<h3>MAILING ADDRESS</h3>
						2, Good Earth Enclave<br />
						Uttarhalli Road, Kengeri<br />
						Bangalore 560060<br />
						India<br />
						<br />
						<h3>CAMPUS</h3>
						Village Varadenahalli<br />
						Magadi Taluka<br />
						Ramanagara District 562 120<br />
						India<br />
						<br />
						<h3>EMAIL</h3>
						<a href="mailto:info@cfl.in">info@cfl.in</a><br />
						<br />
						<h3>PHONE</h3><br />
						+91-080-27705748<br />
						+91-(0)9880833966<br />

					</div><!-- end .contact-info -->
				
				</div>
			
			</div><!-- end .container -->
		
		</section><!-- end .content -->
					
	</main><!-- end main -->
	
<?php get_footer(); ?>