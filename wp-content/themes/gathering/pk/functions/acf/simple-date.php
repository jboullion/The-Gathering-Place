<?php
	class acf_field_simple_date extends acf_field {
		
		function __construct() {
			$this->name = 'simple_date';
			$this->label = 'Date';
			$this->category = 'basic';
			parent::__construct();
		}
		
		function render_field($field) {
			
			// eventually add this as a configurable option for year min and year max if needed
			$year_reach = 10;

			if($value = strtotime(esc_attr($field['value']))) {
				$month = date('m', $value);
				$day = date('d', $value);
				$year = date('Y', $value);
			}
			?>
			<select name="<?php echo esc_attr($field['name']) ?>[month]" style="float:left; margin-right:5px; width:120px;">
				<option value=""> - Month - </option>
				<?php
					for($x = 1; $x <= 12; $x++) {
						$val = str_pad($x, 2, '0', STR_PAD_LEFT);
						echo '<option value="'.$val.'"'.($month == $val ? ' selected="selected"' : '').'>'.date('F', mktime(0, 0, 0, $val, 1)).'</option>';
					}
				?>
			</select>
			<select name="<?php echo esc_attr($field['name']) ?>[day]" style="float:left; margin-right:5px; width:80px;">
				<option value=""> - Day - </option>
				<?php
					for($x = 1; $x <= 31; $x++) {
						$val = str_pad($x, 2, '0', STR_PAD_LEFT);
						echo '<option value="'.$val.'"'.($day == $val ? ' selected="selected"' : '').'>'.$val.'</option>';
					}
				?>
			</select>
			<select name="<?php echo esc_attr($field['name']) ?>[year]" style="float:left; width:80px;">
				<option value=""> - Year - </option>
				<?php
					for($x = date('Y') - $year_reach; $x <= date('Y') + $year_reach; $x++) {
						echo '<option value="'.$x.'"'.($year == $x ? ' selected="selected"' : '').'>'.$x.'</option>';
					}
				?>
			</select>
			<div style="clear:both;"></div>
			<?php
		}
			
		function update_value($value, $post_id, $field) {
			return $value['year'].$value['month'].$value['day'];
		}
	}

	new acf_field_simple_date();