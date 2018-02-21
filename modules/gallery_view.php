<?php GLOBAL $chassis_list, $with_paint_job, $with_accessory; ?>
<div class="container">
	<div class="row">
		<ul class="tabs">
			<li class="tab col s6"><a class="active waves-effect" href="#ets2">Euro Truck Simulator 2</a></li>
			<li class="tab col s6"><a href="#ats" class="waves-effect">American Truck Simulator</a></li>
		</ul>
		<div id="ets2" class="game">
			<h4 class="light"><?= t('ets_trailer_guide') ?></h4>
			<?php $chassis = array();
			foreach($chassis_list['ets2'] as $key => $value){
				$chassis[] = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey', '_wall', '_floor', '_glass'], '', $key);
			}
			$diff = [
				'car_transporter',
				'overweight',
				'goldhofer_mpa',
				'goldhofer_stz',
			];
			foreach(array_values(array_unique($chassis)) as $key => $name) :?>
				<div class="col m6 s12">
					<div class="card trailer <?= $key ?>">
						<div class="card-image">
							<img src="/assets/img/trailers/<?= $name ?>/<?= $name ?>.jpg">
							<h5 class="card-title trailer-name text-shadow"><?= str_replace(' - ', '<br>', t($name)) ?></h5>
						</div>
						<?php $name_alt = in_array($name, $diff) ? $name . '_default' : $name;
						if(key_exists($name_alt, $with_paint_job) || in_array($name_alt, $with_accessory)) : ?>
							<div class="card-content">
								<ul class="collapsible show-skin z-depth-0" data-trailer="<?= $name_alt ?>" data-game="ets2">
									<li>
										<div class="collapsible-header">
											<i class="material-icons notranslate">arrow_downward</i>
											<?php if(key_exists($name_alt, $with_paint_job)) : ?>
												<span style="flex: 1;"><?= t('see_paints') ?></span>
											<?php elseif(in_array($name_alt, $with_accessory)): ?>
												<span style="flex: 1;"><?= t('see_cargo') ?></span>
											<?php endif ?>
										</div>
										<div class="collapsible-body"></div>
									</li>
								</ul>
							</div>
						<?php endif ?>
					</div>
				</div>
				<?php if($key % 2 != 0) : ?>
				    <div class="clearfix"></div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
		<div id="ats" class="game">
			<h4 class="light"><?= t('ats_trailer_guide') ?></h4>
			<?php $chassis = array();
			foreach($chassis_list['ats'] as $key => $value){
				$chassis[] = str_replace(['_default', '_black', '_yellow', '_red', '_blue', '_grey', '_1', '_4', '_3'], '', $key);
			}
			$diff = [
				'lowboy' => 'lowboy_black',
				'gooseneck_ats' => 'gooseneck_ats_blue',
				'flatbed' => 'flatbed_black',
				'car' => 'car_black',
				'dump' => 'dump_black',
			];
			foreach(array_values(array_unique($chassis)) as $key => $name) :?>
				<div class="col m6 s12">
					<div class="card trailer <?= $key ?>">
						<div class="card-image">
							<img src="/assets/img/trailers/<?= $name ?>/<?= $name ?>.jpg">
							<h5 class="card-title trailer-name text-shadow"><?= t($name) ?></h5>
						</div>
						<?php $name_alt = key_exists($name, $diff) ? $diff[$name] : $name;
							if(key_exists($name_alt, $with_paint_job) || in_array($name_alt, $with_accessory)) : ?>
								<div class="card-content">
									<ul class="collapsible show-skin z-depth-0" data-trailer="<?= $name_alt ?>" data-game="ats">
										<li>
											<div class="collapsible-header">
												<i class="material-icons notranslate">arrow_downward</i>
												<?php if(key_exists($name_alt, $with_paint_job)) : ?>
													<span style="flex: 1;"><?= t('see_paints') ?></span>
												<?php elseif(in_array($name_alt, $with_accessory)): ?>
													<span style="flex: 1;"><?= t('see_cargo') ?></span>
												<?php endif ?>
											</div>
											<div class="collapsible-body"></div>
										</li>
									</ul>
								</div>
							<?php endif ?>
					</div>
				</div>
				<?php if($key % 2 != 0) : ?>
					<div class="clearfix"></div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>
</div>