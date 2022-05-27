<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'j96108q7_legsite' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'j96108q7_legsite' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', 'Dn100883' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '<KBM<*XY j:h*/Bdnghwy@kiwu6.uc7<Ku,,UJfsfmYhP(HSD+ZbuIcz4ya,i8xs' );
define( 'SECURE_AUTH_KEY',  '5[CRq=$4i*3?P}k}VL7[Gqaqf77gz5Ol$T+%[i+Rf,dvW~]i3HuJ%m o)30.<G</' );
define( 'LOGGED_IN_KEY',    '$.b`z?~@ D_:wx>:pL{IA[s.~`a[F,RVe0440Z7`M]dz7bm#.*4$9T>!s-]3eKFH' );
define( 'NONCE_KEY',        'sgdZ`0/FwiXslI[Ls_tKgTvHFWF&,0v&+Lpd~,MB:K/lm3R<keO-u3H^D2d.-Jj=' );
define( 'AUTH_SALT',        'VEWqM^&`g!#7!Ml:sB2 |$Vs]gN4IXMBGu5dP6a&CSFdM[4F$nHq< $6}E!Op.hF' );
define( 'SECURE_AUTH_SALT', 'c:=e(p0wu>C4r/zM>  =nWbh:-M~2fx+1bRw&AM8`)SlX7-eh1]1wJT`ZkEp,W|9' );
define( 'LOGGED_IN_SALT',   'B/H<7-o_l&#2a@/Z9_S@uw$@YTx22{[hE8OytapyId5lv}^T47R&aXS9Cm9lgXga' );
define( 'NONCE_SALT',       'o@5;2C[vJyH<3Li*44uVa-|]4!K)/<-bb_^8RG<03x+Q7&1/hSqcoW!VTK~F[/W6' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
