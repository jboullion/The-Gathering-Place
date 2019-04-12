<div id="dm-tools" class="user-game-actions">
	<div id="tool-select" class="dm-tool">
		<div id="tool-holder" class="dm-holder">
			<div id="current-tile" class="tile" style="background-color: #526F35"></div>
			<div class="tool-spacer"></div>
			<div id="hand-tool" class="tool active" title="Normal"><i class="fas fa-hand-paper"></i></div>
			<div id="paint-tool" class="tool" title="Paint"><i class="fas fa-fw fa-paint-roller"></i></div>
			<div id="fill-tool" class="tool" title="Fill"><i class="fas fa-fw fa-fill-drip"></i></div>
			<div id="erase-tool" class="tool" title="Erase"><i class="fas fa-fw fa-eraser"></i></div>
			<div id="highlight-tool" class="tool" title="Highlight"><i class="fas fa-fw fa-highlighter"></i></div>
			<div id="sample-tool" class="tool" title="Sample"><i class="fas fa-eye-dropper"></i></div>
			<div class="tool-spacer"></div>
			<div id="color-tool" class="visual-tool" title="Color"><input id="tile-color" type="color" value="#526F35" class="fas fa-palette" /></div>
			<div id="texture-tool" class="visual-tool" title="Texture" data-toggle="modal" data-target="#texture-modal"><i class="fas fa-images"></i></div>
			<div class="tool-spacer"></div>
			<div id="save-tool" class="visual-tool" title="Save" data-toggle="modal" data-target="#save-modal"><i class="fas fa-save"></i></div>
				
			<!--
				<div id="load-tool" class="visual-tool" title="Open"><i class="fas fa-folder-open"></i></div>
				<div id="new-tool" class="visual-tool" title="New"><i class="fas fa-file-plus"></i></div>
				<div id="copy-tool" class="visual-tool" title="Duplicate"><i class="fas fa-files-medical"></i></div>
				<div id="import-tool" class="visual-tool" title="Import"><i class="fas fa-file-import"></i></div>
				<div id="clear-tool" class="visual-tool" title="Clear"><i class="fas fa-undo-alt"></i></div>
				<div id="new-layer-tool" class="visual-tool" title="New Layer"><i class="fas fa-layer-plus"></i></div>
			-->

			
		</div>
	</div>

</div>

<div id="texture-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="fas fa-window-close" aria-hidden="true"></i>
				</button>
				
				<h5 class="modal-title">Select Texture</h5>
				<div id="texture-holder" data-dismiss="modal" class="dm-holder"></div>
			</div>
		</div>
	</div>
</div>


<div id="save-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="save-form">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="fas fa-window-close" aria-hidden="true"></i>
					</button>
					
					<h5 class="modal-title">Board Name:</h5>
					<input id="board-name" name="board_name" value="" maxlength="50" required/>

					<label for="auto-save"><input type="radio" id="auto-save" name="autosave" value="1" /> Autosave this board while you play?</label>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="load-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="fas fa-window-close" aria-hidden="true"></i>
				</button>
				
				<h5 class="modal-title">Your Boards:</h5>
				<div id="board-list">
					<ul>
						<li>Board 1</h1>
					</ul>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>