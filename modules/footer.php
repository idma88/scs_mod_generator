</main>
<footer class="container">
	<div class="row">
		<div class="col m6 s12 center">
			<h4 class="light">1.30.*</h4>
			<p class=""><?= t('supported_ets_version') ?></p>
		</div>
		<div class="col m6 s12 center">
			<h4 class="light">1.30.*</h4>
			<p class=""><?= t('supported_ats_version') ?></p>
		</div>
	</div>
	<div class="theme center">
		<div class="mdc-switch">
			<input type="checkbox" id="toggle-dark" class="mdc-switch__native-control"<?php if(isset($_COOKIE['dark_theme']) && $_COOKIE['dark_theme'] == 'true'): ?> checked<?php endif ?>>
			<div class="mdc-switch__background">
				<div class="mdc-switch__knob"></div>
			</div>
		</div>
		<label for="toggle-dark" class="mdc-switch-label"><?= t('dark_theme') ?></label>
	</div>
	<div class="version center">
		<p><?= t('current_version') ?> - 0.13.5</p>
	</div>
	<div class="row center">
		<p><a href="https://volvovtc.com" target="_blank"><?= t('vtc') ?> Volvo Trucks</a></p>
		<span class="footer-copyright">&copy; <a href="https://vk.com/viiper94" target="_blank">viiper94</a> - <?= date('Y') ?></span>
	</div>
</footer>
<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="assets/mdc/js/material-components-web.min.js"></script>
<script type="text/javascript" src="assets/semanticui/transition.min.js"></script>
<script type="text/javascript" src="assets/semanticui/dropdown.min.js"></script>
<script type="text/javascript" src="assets/materialize/js/materialize.min.js"></script>
<script type="text/javascript" src="assets/js/script.js?v0.11.4"></script>
</body>
</html>