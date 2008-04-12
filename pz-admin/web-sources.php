<?php
require_once('../pz-config.php');
require_once('social-networks.php');

if ( isset($_POST['save']) )
{
	// open the profile data
	$sources = new WebDataSources();
	
	// choose the form process
	if ( isset($_POST['form_name']) && $_POST['form_name'] != '' )
	{
		switch ($_POST['form_name'])
		{
			case 'sn_form' :
				$network_id = $_POST['network_id'];
				$username = $_POST['username'];
				$sources->profiles[] = array($network_id, $social_networks[$network_id], $username);
				$messages[] = array('success', 'Successfully added ' . $social_networks[$network_id][0] . ' to your profile list.');
				break;
			
			case 'delete_sn_form' :
				$delete_id = $_POST['delete_id'];
				$messages[] = array('success', $sources->profiles[$delete_id][1][0] . ' has been removed from your profile list.');
				unset($sources->profiles[$delete_id]);
				break;
			
			case 'wds_form' :
				$source_title = $_POST['source_title'];
				$source_url = $_POST['source_url'];
				// here we should use simplepie to:
				//   a. verify that it's an atom/rss feed
				//   b. if it's a webpage, then use simplepie's auto-discovery to get the atom/rss feed
				//   c. alert the user that it's not a valid feed url
				$sources->sources[] = array($source_title, $source_url);
				$messages[] = array('success', 'Successfully added ' . $source_title . ' to your data-source list.');
				break;
			
			case 'delete_wds_form' :
				$delete_id = $_POST['delete_id'];
				$messages[] = array('success', $sources->sources[$delete_id][0] . ' has been removed from your data-source list.');
				unset($sources->sources[$delete_id]);
				break;
			
//			case 'blg_form' :
//				$source_id = $_POST['source_id'];
//				$sources->blogs[] = $source_id;
//				$messages[] = array('success', 'Successfully added ' . $sources->sources[$source_id][0] . ' to your blogs list.');
//				break;
			
//			case 'delete_blg_form' :
//				$delete_id = $_POST['delete_id'];
//				$messages[] = array('success', $sources->sources[$sources->blogs[$delete_id]][0] . ' has been removed from your blogs list.');
//				unset($sources->blogs[$delete_id]);
//				break;
			
//			case 'bkm_form' : 
//				$source_id = $_POST['source_id'];
//				$sources->bookmarks[] = $source_id;
//				break;
			
//			case 'delete_bkm_form' :
//				$delete_id = $_POST['delete_id'];
//				$messages[] = array('success', $sources->sources[$sources->bookmarks[$delete_id]][0] . ' has been removed from your bookmarks list.');
//				unset($sources->bookmarks[$delete_id]);
//				break;
			
//			case 'pht_form' :
//				$source_id = $_POST['source_id'];
//				$sources->photos[] = $source_id;
//				break;
			
//			case 'delete_pht_form' :
//				$delete_id = $_POST['delete_id'];
//				$messages[] = array('success', $sources->sources[$sources->photos[$delete_id]][0] . ' has been removed from your photos list.');
//				unset($sources->photos[$delete_id]);
//				break;
			
//			case 'msc_form' :
//				$source_id = $_POST['source_id'];
//				$sources->music[] = $source_id;
//				break;
			
//			case 'delete_msc_form' :
//				$delete_id = $_POST['delete_id'];
//				$messages[] = array('success', $sources->sources[$sources->music[$delete_id]][0] . ' has been removed from your music list.');
//				unset($sources->music[$delete_id]);
//				break;
			
			case 'modules_form' :
				if ( isset($_POST['blogs']) && is_array($_POST['blogs']) )
					$sources->blogs = $_POST['blogs'];
				else
					unset($sources->blogs);
				
				if ( isset($_POST['bookmarks']) && is_array($_POST['bookmarks']) )
					$sources->bookmarks = $_POST['bookmarks'];
				else
					unset($sources->bookmarks);
				
				if ( isset($_POST['photos']) && is_array($_POST['photos']) )
					$sources->photos = $_POST['photos'];
				else
					unset($sources->photos);
				
				if ( isset($_POST['music']) && is_array($_POST['music']) )
					$sources->music = $_POST['music'];
				else
					unset($sources->music);
				
				$messages[] = array('success', 'Your content modules have been updated.');
				break;
			
			default :
				break;
		}
	}
	
	// save and close the profile data
	$sources->save();
	unset($sources);
}

include_once('admin-header.php');
?>
		<h2>Web Data Sources</h2>
		<?php do_messages(); ?>
		
		<form name="add-network" id="add-network" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-network" onsubmit="javascript:return (this.network_id.value != '-1');">
			<fieldset>
				<legend>Add a Social Network</legend>
				<p>List your social network profiles here.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="sn_form" />
				<div>
					<label for="id_network_id">Select a social network</label>
					<select name="network_id" id="id_network_id">
						<option class="select" value="-1">Pick one...</option>
					<?php foreach ($social_networks as $name => $site) : ?>
						<option class="<?php echo $name; ?>" value="<?php echo $name; ?>"><?php echo $site[0]; ?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div>
					<label for="id_username">Username / User ID</label>
					<input id="id_username" type="text" name="username" maxlength="32" value="" />
					<p class="note">Bebo, Facebook? Use the number in the URL of your 'Profile' page (e.g. <?php $rand = rand(100000000, 999999999); echo $rand; ?>)</p>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Add Social Network" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-source" id="add-source" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-source">
			<fieldset>
				<legend>Add a Web Data Source (Atom/RSS feed)</legend>
				<p>This section is to list all of your web data sources that you want to use within Pez. You can add any Atom and RSS feeds that you like, with no restrictions.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="wds_form" />
				<div>
					<label for="id_source_title">Title</label>
					<input id="id_source_title" type="text" name="source_title" maxlength="32" value="" />
				</div>
				<div>
					<label for="id_source_url">URL</label>
					<input id="id_source_url" type="text" name="source_url" maxlength="255" value="" />
				</div>
				<div><input type="submit" name="save" id="id_save_2" value="Add Web Data Source" class="button" /></div>
			</fieldset>
		</form>
		
		<div id="data-sources">
			<div class="profiles">
				<h4>profiles</h4>
				<?php echo profile_list(true); ?>
			</div>
			<div class="sources">
				<h4>sources</h4>
				<?php echo source_list(false, true); ?>
			</div>
		</div>
		


<script src="../pz-includes/js/jquery.dimensions.js"></script>
<script src="../pz-includes/js/ui.mouse.js"></script>
<script src="../pz-includes/js/ui.draggable.js"></script>
<script src="../pz-includes/js/ui.draggable.ext.js"></script>
<script src="../pz-includes/js/ui.droppable.js"></script>
<script src="../pz-includes/js/ui.droppable.ext.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{

		$("#web-data-sources>ul.sources>li").draggable({helper:'clone',cursor:'move'});
		$("ul.sources>li>a").click( function(){alert( $("form#modules").serialize() );return false;}).removeAttr("href");
		$("a.remove").click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); return false; });
		
		$(".drop").droppable(
		{
			accept: "#web-data-sources>ul.sources>li",
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui)
			{
				if ( !$(this).children("ul:contains('" + $(ui.draggable).text() + "')").length )
				{
					var block = $(ui.draggable).clone();
					var removeLink = $("<a href='#' class='remove'>x</a>");
					removeLink.click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); return false; });
					block.append( removeLink );
					block.append( $("<input type='hidden' name='" + $(this).attr("id") + "[]' id='" + $(this).attr("id") + "-" + block.attr("id") + "' value='" + block.attr("id") + "' />") );
					
					$(this).children("ul").append( block );
				}
			}
		});

	});
</script>
		<form name="modules" id="modules" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#modules" onsubmit="javascript:return true;">
			<fieldset>
				<legend>Add Web Data Sources to Modules</legend>
				
				<div id="web-data-sources">
					<?php echo source_list(true, false); ?>
				</div>
				
			<div class="drop-boxes">
				
				<div id="blogs" class="drop">
					<h3>Blogs Module</h3>
					<?php echo blog_list(false, true); ?>
				</div>
				
				<div id="bookmarks" class="drop">
					<h3>Bookmarks Module</h3>
					<?php echo bookmark_list(false, true); ?>
				</div>
				
				<div id="photos" class="drop">
					<h3>Photos Module</h3>
					<?php echo photo_list(false, true); ?>
				</div>
				
				<div id="music" class="drop">
					<h3>Music Module</h3>
					<?php echo music_list(false, true); ?>
				</div>
				
			</div>
				
				<div>
					<input type="hidden" name="form_name" id="id_form_name" value="modules_form" />
					<input type="submit" name="save" id="save" value="Save" />
				</div>
				
			</fieldset>
		</form>
		
<?php
include_once('admin-footer.php');
?>