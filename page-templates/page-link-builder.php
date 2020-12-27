<?php
/**
 * Template Name: Link Builder
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header(); ?>




<script>
  var slugify = function(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '-')           // Replace spaces with -
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '-')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
  }

  $(document).ready( function() {
  var queryArray = [];
  
  
  $('input.filter').on('change', function() {
    self = $(this);
    var activeRadio = '';
    
    if(self.is(':checked')) {
       
      if(self.is(':radio')) {
        if(self.val() !== activeRadio) {
          queryArray = $.grep(queryArray, function(item) {
            return item.key !== self.attr('data-key');
          });
          activeRadio = self.attr('data-key');
        }
      }
      
      queryArray.push({
        key: self.attr('data-key'),
        val: self.val()
      });
      
    } else {
      queryArray = $.grep(queryArray, function(item) {
        return item.val !== self.val();
      });
    }


    console.log(queryArray);
    
    if(queryArray.length) {
      $('.queryString').text( function() {
        var q = '';
        $.each( queryArray, function(i, v) {
          q += (i !== 0? '&' : '')+queryArray[i].key+'='+queryArray[i].val;
        });
        return '?'+ q;
      });
    } else {
      $('.queryString').text('');
    }
  });
});

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}



  
</script>
<p>Sort by:</p>
<ul>
  <li>
    <label>
      <input class="filter" type="checkbox" name="country" value="uk" data-key="country"/>United Kingdon
    </label>
  </li>
  <li>
    <label>
      <input class="filter" type="checkbox" name="country" value="fr" data-key="country"/>France
    </label>
  </li>
  <li>
    <label>
      <input class="filter" type="checkbox" name="country" value="ngn" data-key="country"/>Nigeria
    </label>
  </li>
</ul>
<ul>
  <li>
    <label>
      <input class="filter" type="radio" name="sex" value="male" data-key="sex"/>Male
    </label>
  </li>
  <li>
    <label>
      <input class="filter" type="radio" name="sex" value="female" data-key="sex"/>Female
    </label>
  </li>
</ul>
<ul>
  <li>
    <label>
      <input class="filter" type="radio" name="age" value="adult" data-key="age"/>Adult
    </label>
  </li>
  <li>
    <label>
      <input class="filter" type="radio" name="age" value="minor" data-key="age"/>Minor
    </label>
  </li>
</ul>
<p>Share this! = hennepens.com/<span class="queryString"></span></p><svg>


<ul class="products">
	<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</ul>
<?php get_footer(); ?>