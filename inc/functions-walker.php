<?php

/*
===================================================================
          Walker Comments
===================================================================
*/
if (!class_exists('clean_comments_constructor')) { // если класс уже есть в дочерней теме - нам не надо его определять
	class clean_comments_constructor extends Walker_Comment { // класс, который собирает всю структуру комментов
		public function start_lvl( &$output, $depth = 0, $args = array()) { // что выводим перед дочерними комментариями
			$output .= '<ul class="children">' . "\n";
		}
		public function end_lvl( &$output, $depth = 0, $args = array()) { // что выводим после дочерних комментариев
			$output .= "</ul><!-- .children -->\n";
		}
		protected function comment( $comment, $depth, $args ) { // разметка каждого комментария, без закрывающего </li>!
			$classes = implode(' ', get_comment_class()).($comment->comment_author_email == get_the_author_meta('email') ? ' author-comment' : ''); // берем стандартные классы комментария и если коммент пренадлежит автору поста добавляем класс author-comment
			echo '<li id="comment-'.get_comment_ID().'" class="'.$classes.' media">'."\n"; // родительский тэг комментария с классами выше и уникальным якорным id
			echo '<div class="media-left">'.get_avatar($comment, 64, '', get_comment_author(), array('class' => 'media-object'))."</div>\n"; // покажем аватар с размером 64х64
			echo '<div class="media-body">';
			echo '<span class="meta media-heading">'. __('Автор:', 'garage' ) .' '.get_comment_author()."\n"; // имя автора коммента
			//echo ' '.get_comment_author_email(); // email автора коммента, плохой тон выводить почту
			echo ' '.get_comment_author_url(); // url автора коммента
			echo ' Добавлено '.get_comment_date('F j, Y в H:i')."\n"; // дата и время комментирования
			if ( '0' == $comment->comment_approved ) echo '<br><em class="comment-awaiting-moderation">'. __('Ваш комментарий будет опубликован после проверки модератором.', 'garage' ) .'</em>'."\n"; // если комментарий должен пройти проверку
			echo "</span>";
			comment_text();
			echo "<br>"; // текст коммента
			$reply_link_args = array( // опции ссылки "ответить"
				'depth' => $depth, // текущая вложенность
				'reply_text' => __( 'Ответить', 'garage' ), // текст
				'login_text' => __( 'Вы должны быть залогинены', 'garage' ) // текст если юзер должен залогинеться
			);
			echo get_comment_reply_link(array_merge($args, $reply_link_args)); // выводим ссылку ответить
			echo '</div>'."\n"; // закрываем див
		}
		public function end_el( &$output, $comment, $depth = 0, $args = array() ) { // конец каждого коммента
			$output .= "</li><!-- #comment-## -->\n";
		}
	}
}

/*
===================================================================
          Walker Menu
===================================================================
*/
if (!class_exists('garage_menu')) {
	class garage_menu extends Walker_Nav_Menu { // внутри вывод
		private $open_submenu_on_hover; // параметр который будет определять раскрывать субменю при наведении или оставить по клику как в стандартном бутстрапе

		function __construct($open_submenu_on_hover = true) { // в конструкторе
			$this->open_submenu_on_hover = $open_submenu_on_hover; // запишем параметр раскрывания субменю
		}

		function start_lvl(&$output, $depth = 0, $args = array()) { // старт вывода подменюшек
			$output .= "\n<ul class=\"dropdown-menu\">\n"; // ул с классом
		}
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) { // старт вывода элементов
			$item_html = ''; // то что будет добавлять
			parent::start_el($item_html, $item, $depth, $args); // вызываем стандартный метод родителя
			if ( $item->is_dropdown && $depth === 0 ) { // если элемент содержит подменю и это элемент первого уровня
				if (!$this->open_submenu_on_hover) $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"', $item_html); // если подменю не будет раскрывать при наведении надо добавить стандартные атрибуты бутстрапа для раскрытия по клику
				$item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html); // ну это стрелочка вниз
			}
			$output .= $item_html; // приклеиваем теперь
		}
		function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) { // вывод элемента
			if ( $element->current ) $element->classes[] = 'active'; // если элемент активный надо добавить бутстрап класс для подсветки
			$element->is_dropdown = !empty( $children_elements[$element->ID] ); // если у элемента подменю
			if ( $element->is_dropdown ) { // если да
				if ( $depth === 0 ) { // если li содержит субменю 1 уровня
					$element->classes[] = 'dropdown'; // то добавим этот класс
					if ($this->open_submenu_on_hover) $element->classes[] = 'show-on-hover'; // если нужно показывать субменю по ховеру
				} elseif ( $depth === 1 ) { // если li содержит субменю 2 уровня
					$element->classes[] = 'dropdown-submenu'; // то добавим этот класс, стандартный бутстрап не поддерживает подменю больше 2 уровня по этому эту ситуацию надо будет разрешать отдельно
				}
			}
			parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); // вызываем стандартный метод родителя
		}
	}
}