<?php
class Chart {
	protected $label = array();
	protected $data = array();
	protected $info = array();
	protected $dsopts = array();
	protected $opts = array();
	protected $myid;
	protected $fullwidth = false;

	public $defaults = array(
		'fillColor' => 'rgba(151,187,205,0.5)',
		'strokeColor' => 'rgba(151,187,205,1)',
		'pointColor' => 'rgba(151,187,205,1)',
		'pointStrokeColor' => '#fff'
	);

	public function Chart() {
		$this->myid = md5(microtime() . mt_rand());
	}

	public function makeDataset($info, $data, $label) {
		$ds = count($this->info);

		$this->data = $data;
		$this->label = $label;
		$this->info[$ds] = $info;

		return $ds;
	}

	public function setDataOpt($dataset, $option, $value = '') {
		if (is_array($option))
			$this->dsopts[$dataset] = array_merge(isset($this->dsopts[$dataset]) ? $this->dsopts[$dataset] : array(), $option);
		else
			$this->dsopts[$dataset][$option] = $value;

		return $this;
	}

	public function setChartOpt($option, $value = '') {
		if (is_array($option))
			$this->opts = array_merge($this->opts, $option);
		else
			$this->opts[$option] = $value;

		return $this;
	}

	public function getHTML($width, $height) {
		if ($width == '100%')
			$this->fullwidth = true;

		return '<canvas id="' . $this->myid . '" style="width:' . $width. ';height:' . $height . '" class="chart"></canvas>';
	}

	public function getJS() {

		$out = array();
		$out['labels'] = array();
		$out['datasets'] = array();

		$d = $this->data;
		$nm = $this->label;

		for ($i = 0; $i < count($this->info); $i++) {

			$opts = array_merge($this->defaults, isset($this->dsopts[$i]) ? $this->dsopts[$i] : array());
			$data = array();

			foreach ($this->info[$i] as $in) {
				if (is_array($in))
					$data[] = $in[$d];
				else
					$data[] = $in->$d;

				if ($i === 0) {
					if (is_array($in))
						$out['labels'][] = $in[$nm];
					else
						$out['labels'][] = $in->$nm;
				}
			}
			$opts['data'] = $data;
			$out['datasets'][] = $opts;
		}

		return 'document.addEventListener("DOMContentLoaded", function() { ' . ($this->fullwidth ? '
			newWidth = $("#' . $this->myid . '").parent().width();
			$("#' . $this->myid . '").attr("width", newWidth);
			$("#' . $this->myid . '").width(newWidth);

			' : '' ) . '
			var myLine = new Chart(document.getElementById("' . $this->myid . '").getContext("2d")).Line(' . json_encode($out) . ', ' . json_encode($this->opts) . ');}, false);';
	}
}
?>