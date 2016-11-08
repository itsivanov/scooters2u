<?php
App::uses('Component', 'Controller');
/**
 * jQuery DataTables Component.
 *
 */
class DataTablesComponent extends Component {

	var $sLimit = null;
	var $sNames = null;
	var $model = null;
    public $controller;


	function initialize(Controller $controller, $settings = array()) {
		$this->controller =& $controller;
		foreach ($this->controller->uses as $modelClass){
			$controller->loadModel($modelClass);
			$this->$modelClass = $controller->$modelClass;
		}
	}
	
	
	function output ($dataTables = null){
		if ($dataTables){			
			
			// A way of getting the names of all the columns.
			if ( isset( $this->controller->request->query['sColumns'] ) ){
				$this->sNames = explode(',', $this->controller->request->query['sColumns'] );
			}
            $ordering = array();
			// Are we sorting columns
			if ( isset( $this->controller->request->query['iSortCol_0'] ) ){
				for ( $i=0 ; $i<intval( $this->controller->request->query['iSortingCols'] ) ; $i++ ){
					if ( $this->controller->request->query[ 'bSortable_'.intval( $this->controller->request->query['iSortCol_'.$i] ) ] == "true" ){
						if (isset ($this->sNames)){
							$ordering[] = $dataTables['use'] . '.' . $this->sNames[ intval( $this->controller->request->query['iSortCol_'.$i] ) ] . ' ' . $this->controller->request->query['sSortDir_'.$i];
						}
					}
				}
			}
			
			// If the user is searching
			if ( $this->controller->request->query['sSearch'] != "" ){
				for ( $i=0 ; $i<count($this->sNames) ; $i++ ){
					if ( $this->controller->request->query['bSearchable_'.$i] == "true"){
						$conditions[$dataTables['use'] . '.' . $this->sNames[$i] . ' LIKE'] = '%' . $this->controller->request->query['sSearch'] . '%';
					}
				}
			}
			
			if (isset($conditions) && !empty($conditions)){
				$finalconditions = array('OR'=>$conditions);
			}else{
				$finalconditions = '';
			}
			
			$n = array(
				'conditions' => $finalconditions,
				'recursive' => 1,
				'fields' => $this->sNames,
				'order' => $ordering,
				'limit' => $this->controller->request->query['iDisplayLength'], 
				'offset'=> $this->controller->request->query['iDisplayStart'], 
			);
			
			$rResult = $this->$dataTables['use']->find('all', $n);
			$iTotal = $this->$dataTables['use']->find('count');
			if ($this->controller->request->query['sSearch']){
				$n['limit'] = null;
				$n['offset'] = null;
				$iFilteredTotal = $this->$dataTables['use']->find('count', array('conditions' => $finalconditions));
			}else{
				$iFilteredTotal = $iTotal;
			}
			
			$output = array(
				"aoColumns" => implode(',', $this->sNames),
				"sEcho" => intval($this->controller->request->query['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			foreach ($rResult as $record){
				$row = array();
				for ( $i=0 ; $i<count($this->sNames) ; $i++ ){
					$row[] = $this->filter($this->sNames[$i], $record, $dataTables['use']);
				}
				/* Add buttons as an additional column?
				 * maybe in the form of:
				 * $dataTables['buttons'] = array(
				 * 		array('name' => 'View', 'link' => '/view/%id%', ''),
				 *		'edit' => '/edit/%id$',
				 * );
				 * with urls being able to be hooked into cakes automagic black box :)
				 */
				$output['aaData'][] = $row;
			}
            if (isset($dataTables['buttons'])) $output['buttons'] = $dataTables['buttons'];
			echo json_encode( $output );
			die();
		}else{
			return false;
		}
	}

    private function addButtons($buttonsData){

        $outBtn = '';
        foreach ($buttonsData as $btn)
        {
            foreach ($btn as $name => $link)
            {
                $outBtn .= "<a href='$name'>";
            }

        }

    }

	function filter($input, $record, $tName){

        switch ($input){

            case 'public':
                $output = 'No';
                if ($record[$tName]['public'] == '1'){
                    $output = 'Yes';
                }
                return $output;
                break;
            default:
                return $record[$tName][$input];
                break;
        }
    }

}

?>
