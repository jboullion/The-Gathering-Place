<div id="dm-tools" class="user-game-actions">
	<div id="tool-select" class="dm-tool">
		<div id="tool-holder" class="dm-holder">
			<div id="current-tile" class="tile" style="background-color: #526F35"></div>
			<div class="tool-spacer"></div>
			<div id="hand-tool" class="tool active"><i class="fas fa-hand-paper"></i></div>
			<div id="paint-tool" class="tool"><i class="fas fa-fw fa-paint-roller"></i></div>
			<div id="fill-tool" class="tool"><i class="fas fa-fw fa-fill-drip"></i></div>
			<div id="erase-tool" class="tool"><i class="fas fa-fw fa-eraser"></i></div>
			<div id="highlight-tool" class="tool"><i class="fas fa-fw fa-highlighter"></i></div>
			<div id="sample-tool" class="tool"><i class="fas fa-eye-dropper"></i></div>
			<div class="tool-spacer"></div>
			<div id="color-tool" class="visual-tool"><input id="tile-color" type="color" value="#526F35" class="fas fa-palette" /></div>
			<div id="texture-tool" class="visual-tool" data-toggle="modal" data-target="#texture-modal"><i class="fas fa-images"></i></div>
			
			
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