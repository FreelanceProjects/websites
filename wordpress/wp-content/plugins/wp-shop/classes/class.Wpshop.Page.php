<?php
/**
 * Здесь расположена система разных корзин
 * 
 * @author Igor Bobko
 *
 */

class Wpshop_Page
{
	private $view;
	public function __construct()
	{
		add_action('init', array(&$this,'registerPostType'));
	}
	
	public function registerPostType()
	{
		/** Создаем специальные taxonomy для способов оплаты */
		$this->createCustomTaxonomy();
		
		$labels = array(
			'name' => 'WP Shop Carts', // Основное название типа записи  
			'singular_name' => 'Cart', // отдельное название записи типа Book  
			//'add_new' => 'Добавить новую',  
			'add_new_item' => 'Добавить новую корзину',  
			'edit_item' => 'Редактировать корзину',  
			'new_item' => 'Новая книга',  
			'view_item' => 'Посмотреть книгу',  
			'search_items' => 'Найти книгу',  
			//'not_found' =>  'Книг не найдено',  
			'not_found_in_trash' => 'В корзине книг не найдено',  
			'parent_item_colon' => '',  
			'menu_name' => 'WP Shop Carts'  
		  
		);  
		$args = array(  
			'labels' => $labels,  
			'public' => true,  
			'publicly_queryable' => true,  
			'show_ui' => true,  
			'show_in_menu' => 'wpshop_main',  
			'query_var' => true,  
			'rewrite' => false,  
			'capability_type' => 'page',
			//'capabilities'=>array('edit_post'),
			'has_archive' => true,  
			'hierarchical' => false,  
			'menu_position' => 100,
			//'_edit_link'=>false,
			'supports' => array('title','editor','author','thumbnail','comments','custom-fields'),
			'taxonomies' => array('genre'),
		);  
		register_post_type('wpshopcarts',$args);
		add_filter('manage_edit-wpshopcarts_columns', array($this,'cartColumns')) ;
		// Добавляем необходимую функция для отображения категорий страниц
		add_action( 'manage_wpshopcarts_posts_custom_column', array($this,'devpress_manage_wpshopcarts_columns'), 10, 2 );		
		$this->createCarts();
	}
	
	public function cartColumns()
	{
		return array('cb' => '<input type="checkbox" />',
		'title' => __( 'Платежная страница' ),
		'genre' => __( 'Платеж' ));
	}
	
	/**
	 * Функция отвечает за отображения надписи о категории страниц в WP-SHOP Carts
	 */
	public function devpress_manage_wpshopcarts_columns( $column, $post_id )
	{
		global $post;
			switch( $column ) {
				case 'genre' :
					/* Get the genres for the post. */
					$terms = get_the_terms( $post_id, 'genre' );

					/* If terms were found. */
					if ( !empty( $terms ) )
					{
						$out = array();
						/* Loop through each term, linking to the 'edit posts' page for the specific term. */
						foreach ( $terms as $term )
						{
							$out[] = sprintf( '<a href="%s">%s</a>',
								esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
								esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
							);
						}
						/* Join the terms, separating them with a comma. */
						echo join( ', ', $out );
					}
					/* If no terms were found, output a default message. */
					else
					{
						_e( 'не помечено' );
					}
					break;
				default: break;
			}
	}
	
	public function createCarts()
	{
		$payments = Wpshop_Payment::getInstance()->getPayments();
		$excerpt = array();
		$query = new WP_Query(array ('post_type' => 'wpshopcarts','posts_per_page' => -1 ));
		
		for ($i = 0; $i < count($query->posts); ++$i)
		{
			$excerpt[] = trim($query->posts[$i]->post_excerpt);
		}
		
		foreach($payments as $value)
		{
			if(!in_array($value->paymentID, $excerpt))
			{
				$this->createCart($value);
			}
		}
	}
	
	public function createCart($payment)
	{
		$post = array
		(
			'post_title' => $payment->title,
			'post_content' => "[cart]\n\n<!--cforms name=\"wpshop-{$payment->paymentID}\"-->",
			'post_status' => 'publish',
			'post_type' => 'wpshopcarts',
			'post_excerpt' => $payment->paymentID,
			'post_name' => $payment->paymentID,
			'comment_status'=>'closed',
			'ping_status' => 'closed'
		);
		$id = wp_insert_post($post);
		wp_set_object_terms( $id, $payment->paymentID, "genre", false);
		if ($payment->paymentID == "wm")
		{
			$post = array
			(
				'post_title' => "Ваш платеж через ‘Web-Money’ принят",
				'post_content' => "",
				'post_status' => 'publish',
				'post_type' => 'wpshopcarts',
				'post_excerpt' => "wm_success",
				'post_name' => "wm_success",
				'comment_status'=> 'closed',
				'ping_status' => 'closed'
			);
			$id = wp_insert_post($post);
			wp_set_object_terms( $id, $payment->paymentID, "genre", false);	
			$post = array
			(
				'post_title' => "Ваш платеж через ‘Web-Money’ не принят",
				'post_content' => "",
				'post_status' => 'publish',
				'post_type' => 'wpshopcarts',
				'post_excerpt' => "wm_failed",
				'post_name' => "wm_failed",
				'comment_status'=>'closed',
				'ping_status' => 'closed'
			);
			$id = wp_insert_post($post);
			wp_set_object_terms( $id, $payment->paymentID, "genre", false);
		}
		if ($payment->paymentID == "robokassa")
		{
			$post = array
			(
				'post_title' => "Ваш платеж через систему ‘RoboKassa.ru’ принят",
				'post_content' => "",
				'post_status' => 'publish',
				'post_type' => 'wpshopcarts',
				'post_excerpt' => "robokassa_success",
				'post_name' => "robokassa_success",
				'comment_status'=>'closed',
				'ping_status' => 'closed'
			);
			$id = wp_insert_post($post);
			wp_set_object_terms( $id, $payment->paymentID, "genre", false);	
			$post = array
			(
				'post_title' => "Ваш платеж через систему ‘RoboKassa.ru’ не принят",
				'post_content' => "",
				'post_status' => 'publish',
				'post_type' => 'wpshopcarts',
				'post_excerpt' => "robokassa_failed",
				'post_name' => "robokassa_failed",
				'comment_status'=>'closed',
				'ping_status' => 'closed'
			);
			$id = wp_insert_post($post);
			wp_set_object_terms( $id, $payment->paymentID, "genre", false);
		}
	}
	
	public function createCustomTaxonomy()
	{
		// Вполне очевидно, что отображать мэнэджмент для наших таксономий совсем не нужно и остаеться только создать специальные таксономии и страницы для них
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name' => 'Payment categories',
			'singular_name' => 'Payment categories',
			'search_items' => 'Search Genres',
			'all_items' => 'All Genres',
			'parent_item' => 'Parent Genre',
			'parent_item_colon' => 'Parent Genre:',
			'edit_item' => 'Edit Genre', 
			'update_item' => 'Update Genre' ,
			'add_new_item' => 'Add New Genre',
			'new_item_name' => 'New Genre Name',
			'menu_name' => 'Payment categories',
		); 	

		register_taxonomy('genre',array('post'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => false,
		'show_tagcloud' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'genre' ),
		));
		$payments = Wpshop_Payment::getInstance()->getPayments();
		foreach($payments as $payment)
		{
			if (!term_exists($payment->paymentID, "genre"))
			{
				wp_insert_term($payment->name, 'genre', array('slug' => $payment->paymentID));
			}
		}
	}
}