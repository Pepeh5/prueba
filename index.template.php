<?php

function template_init()
{
	global $context, $settings, $options, $txt;

	$settings['use_default_images'] = 'never';

	$settings['doctype'] = 'xhtml';

	$settings['theme_version'] = '2.0';

	$settings['use_tabs'] = true;

	$settings['use_buttons'] = true;

	$settings['separate_sticky_lock'] = true;

	$settings['strict_doctype'] = false;

	$settings['message_index_preview'] = false;

	$settings['require_theme_strings'] = false;
}


function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	 echo '  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	 <script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

echo '
</head>
<body>';
}





function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	
	$usuario = $context['user']['name'];
	
	$query = mysql_query("SELECT * FROM usuarios WHERE Username = '".$usuario."'");
	while($row = mysql_fetch_assoc($query))
	{
		$ropa = $row['Skin'];
	}
	
	echo '
	<div id="bodybg">';
	echo !empty($settings['forum_width']) ? '
			<div id="wrapper">' : '', '
			  <div id="header">
				<div id="head-l">
					 <div id="head-r">
						  <div id="userarea" class="smalltext">';
			if ($context['user']['is_logged']){
			
			echo '
			<div id="my-avatar" class="clearfix">
				
				
				<img src="./avatars/skin/'.$ropa.'.png" />
				
			</div>';

			$quitargion=str_replace("_"," ",$context['user']['name']);
			
			echo '
		  <div class="logged-usuario">';
			echo '
				 <br><br><center>', strtoupper($quitargion), '</center>
				 <br>&nbsp;<a href="', $scripturl, '?action=unread">&raquo;&nbsp;', $txt['unread_since_visit'], '</a>
				 <br>&nbsp;<a href="', $scripturl, '?action=unreadreplies">&raquo;&nbsp;', $txt['show_unread_replies'], '</a>
			</div>';
		}

	// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
		else
	  {
		  echo sprintf($txt['welcome_guest'], $txt['guest_title']);
		echo '<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
		<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" style="margin: 4px 0;"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
				<b>Usuario :</b>&nbsp;<input type="text" name="user" class="inputs"  size="10" /><br/>
				<b>Contrase&ntildea : </b>&nbsp;<input type="password" name="passwrd"  size="10" class="inputs" /><br/>
				<input type="submit" value="Ingresar" class="button_submit" />
				<input type="hidden" name="hash_passwrd" value="" />
			</form>', $context['current_time'],'<br />';
	  }
	echo '
	  </div>
<div class="botones-superiores"><a href="https://www.facebook.com/extegre.rp/" title="Cuenta oficial de Facebook" class="facebook-boton" target="_blank" border="0"></a>&nbsp;<a href="https://www.youtube.com/channel/UCKFFVg8kdAUjUtPSrS7i6CQ/" title="Cuenta oficial de YouTube" class="youtube-boton" target="_blank" border="0"></a></div>
		<div id="searcharea">
		<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
			  <input class="inputbox" type="text" name="search" value="', $txt['search'], '..." onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['search'], '...\';" /> ';

	// Search within current topic?
	if (!empty($context['current_topic']))
	echo '
			 <input type="hidden" name="topic" value="', $context['current_topic'], '" />';

	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
	echo '
			<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '
	</form>
		</div>';

  // Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news'])){
		echo '<div id="news">
		  <br />', $context['random_news_line'], '</div>';}
		  echo '
				 <div id="logo">
			 <a href="'.$scripturl.'" title=""></a>
		 </div>';
		echo '
		  </div>
	  </div>
  </div>
	<div id="navbar">
	',template_menu(),'
	</div>
		  <div id="bodyarea">';
			  theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

			 echo '
		</div>';


	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
<div id="footer">
	 <div id="foot-l">
		  <div id="foot-r">
			  <div id="footerarea" class="normaltext">
					<div id="footer_section" class="frame">
				<ul class="reset">
				<li class="copyright">', theme_copyright(), '</li>
				<li><b>GZ:RP</b> by <a href="http://kropper.cf/" target="_blank" class="new_win" title=""><span><b>Kropper
		</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p><br />', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

		echo '
			</div>
		 </div>
	  </div>
	</div>
 </div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	</div>
 </div>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	// Reverse the linktree in right to left mode.
	if ($context['right_to_left'])
		$context['linktree'] = array_reverse($context['linktree'], true);

	echo '
	<div class="navigate_section">
		<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Don't show a separator for the last one (RTL mode)
		if ($link_num != count($context['linktree']) - 1 && $context['right_to_left'])

			// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>&nbsp;&#187;' : '<span>' . $tree['name'] .'</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1 && !$context['right_to_left'])

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div id="navi">
			<ul>';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>', $button['title'], '</a>';

		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '</a>';

				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>', $grandchildbutton['title'], '</a>
								</li>';

					echo '
						</ul>';
				}

				echo '
						</li>';
			}
			echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			</ul>
		</div>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '<a ' . (isset($value['active']) ? 'class="active" ' : '') . 'href="' . $value['url'] . '" ' . (isset($value['custom']) ? $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' align_' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>
				<li>', implode('</li><li>', $buttons), '</li>
			</ul>
		</div>';
}

?>