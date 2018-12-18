<?php
/**
 * @version		$Id: questions.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class SurveyFormFields {
	
	private $_wysiwyg = false;
	private $_bbcode = false;
	private $_content = false;
	private $_countries = null;
	
	function __construct($wysiwyg, $bbcode, $content){
		
		$this->_wysiwyg = $wysiwyg;
		$this->_bbcode = $bbcode;
		$this->_content = $content;
	}
	
	public function get_page_header_question($item, $class){
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_radio_question($item, $class){
		
		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';
		
		$html .= '<div class="answers'.($item->orientation == 'H' ? '' : ' form-inline').' margin-bottom-20">';

			foreach ($item->answers as $i=>$answer){
				
				$selected = false;
				
				if(!empty($item->responses)){
					
					foreach ($item->responses as $response){
						
						if($answer->id == $response->answer_id){
							
							$selected = true;
						} else if(!empty($response->free_text)){
							
							$free_text = CJFunctions::escape($response->free_text);
						}
					}
				}
								
				$html .= '<label class="radio margin-right-20">';
					$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-5'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
					$html .= CJFunctions::escape($answer->answer_label);
				$html .= '</label>';
			}
		
		$html .= '</div>';

		if($item->custom_choice == 1){
			
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge valid" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'" aria-required="false">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_checkbox_question($item, $class){

		$selected = false;
		$free_text = '';
		$range_selection = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers'.($item->orientation == 'H' ? '' : ' form-inline').' margin-bottom-20">';
		
		if($item->max_selections > 0 && $item->max_selections >= $item->min_selections)
		{
			$range_selection = ' minselect="'.$item->min_selections.'" maxselect="'.$item->max_selections.'"';
		}
		
		foreach ($item->answers as $i=>$answer){

			$selected = false;
			
			if(!empty($item->responses)){
				
				foreach ($item->responses as $response){
						
					if($answer->id == $response->answer_id){
				
						$selected = true;
					} else if(!empty($response->free_text)){
						
						$free_text = CJFunctions::escape($response->free_text);
					}
				}
			}
			
			$html .= '<label class="checkbox margin-right-20">';
				$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'"'.$range_selection.
							' class="margin-right-5'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
				$html .= CJFunctions::escape($answer->answer_label);
			$html .= '</label>';
		}
		
		$html .= '</div>';

		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_select_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers">';
			$html .= '<select name="answer-'.$item->id.'"'.($item->mandatory ? ' class="required"' : '').'>';
				$html .= '<option value="">'.JText::_('LBL_SELECT_OPTION').'</option>';

				foreach ($item->answers as $i=>$answer){

					$selected = false;
					
					if(!empty($item->responses)){
						
						foreach ($item->responses as $response){
								
							if($answer->id == $response->answer_id){
						
								$selected = true;
							} else if(!empty($response->free_text)){
								
								$free_text = CJFunctions::escape($response->free_text);
							}
						}
					}
					
					$html .= '<option value="'.$answer->id.'"'.($selected ? ' selected="selected"' : '').'>'.CJFunctions::escape($answer->answer_label).'</option>';
				}
				
			$html .= '</select>';
		$html .= '</div>';

		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_grid_radio_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<table class="table table-hover table-bordered table-striped margin-bottom-20">';
		$html .= '<thead><tr><th></th>';
		
		foreach ($item->answers as $answer){
			
			if($answer->answer_type == 'y'){
				
				$html .= '<th style="text-align: center;">'.CJFunctions::escape($answer->answer_label).'</th>';
			}
		}
		
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $i=>$answer){
			
			if($answer->answer_type == 'x'){
				
				$html .= '<tr><td>'.CJFunctions::escape($answer->answer_label).'</td>';
				
				foreach ($item->answers as $j=>$column){
					
					if($column->answer_type == 'y'){

						$selected = false;
						
						if(!empty($item->responses)){
	
							foreach ($item->responses as $response){
									
								if(($response->answer_id == $answer->id) && ($response->column_id == $column->id)){
							
									$selected = true;
								} else if(!empty($response->free_text)){
										
									$free_text = CJFunctions::escape($response->free_text);
								}
							}
						}
												
						$html .= '<td style="text-align: center;">';
						$html .= '<input type="radio" name="answer-'.$item->id.'-'.$answer->id.'" value="'.$column->id.'"'.($item->mandatory ? ' class="required"' : '').($selected ? ' checked="checked"' : '').'/>';
						$html .= '</td>';
					}
				}
				
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody></table>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_grid_checkbox_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<table class="table table-hover table-bordered table-striped margin-bottom-20">';
		$html .= '<thead><tr><th></th>';
		
		foreach ($item->answers as $answer){
			
			if($answer->answer_type == 'y'){
				
				$html .= '<th class="center">'.CJFunctions::escape($answer->answer_label).'</th>';
			}
		}
		
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $i=>$answer){
			
			if($answer->answer_type == 'x'){
				
				$html .= '<tr><td>'.CJFunctions::escape($answer->answer_label).'</td>';
				
				foreach ($item->answers as $j=>$column){

					if($column->answer_type == 'y'){

						$selected = false;
						
						if(!empty($item->responses)){
	
							foreach ($item->responses as $response){
									
								if(($response->answer_id == $answer->id) && ($response->column_id == $column->id)){
							
									$selected = true;
								} else if(!empty($response->free_text)){
										
									$free_text = CJFunctions::escape($response->free_text);
								}
							}
						}
												
						$html .= '<td class="center">';
						$html .= '<input type="checkbox" name="answer-'.$item->id.'-'.$answer->id.'[]" value="'.$column->id.'"'.($item->mandatory ? ' class="required"' : '').($selected ? ' checked="checked"' : '').'/>';
						$html .= '</td>';
					}
				}
				
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody></table>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_single_line_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge'.($item->mandatory ? ' required' : '').'" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_multiline_textarea_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<textarea name="free-text-'.$item->id.'" cols="25" rows="3" class="input-xxlarge'.($item->mandatory ? ' required' : '').'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">'.$free_text.'</textarea>';
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_password_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<input type="password" name="free-text-'.$item->id.'" class="input-xlarge'.($item->mandatory ? ' class="required"' : '').'" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_rich_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = $response->free_text;
					break;
				}
			}
		}
				
		$editor = $this->_wysiwyg ? ($this->_bbcode ? 'bbcode' : 'wysiwyg') : 'none';
		$html .= CJFunctions::load_editor($editor, 'free-text-'.$item->id, 'free-text-'.$item->id, $free_text, '5', '23', '100%', '200px', ($item->mandatory ? 'required' : ''), 'width: 99%;');
		
		$html .= '</div>';
		
		return $html;
	}

	public function get_image_radio_question($item, $class, $base_uri){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';

		$html .= '<div class="answers clearfix"><div class="row-fluid">';

		foreach ($item->answers as $i=>$answer){
		
			$selected = false;
		
			if(!empty($item->responses)){
				
				foreach ($item->responses as $response){
						
					if($answer->id == $response->answer_id){
			
						$selected = true;
					} else if(!empty($response->free_text)){
			
						$free_text = CJFunctions::escape($response->free_text);
					}
				}
			}
			
			if($item->orientation == 'H'){
			
				$html .= '<div class="margin-bottom-20" style="overflow:auto;">';
					$html .= '<div class="form-inline margin-bottom-10">';
						$html .= '<label class="radio margin-top-10">';
							$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
							$html .= CJFunctions::escape($answer->answer_label);
						$html .= '</label>';
					$html .= '</div>';
					$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'" height="100" width="100">';
				$html .= '</div>';
			} else {
			
				$html .= '<div class="thumbnail span2 margin-bottom-20" style="overflow:auto;">';
					$html .= '<div class="center">';
						$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'" height="100" width="100">';
						$html .= '<div class="form-inline">';
							$html .= '<label class="radio margin-top-10">';
								$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
								$html .= CJFunctions::escape($answer->answer_label);
							$html .= '</label>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		
		$html .= '</div>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div></div>';
		
		return $html;
	}

	public function get_image_checkbox_question($item, $class, $base_uri){

		$free_text = '';
		$range_selection = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';

		$html .= '<div class="answers clearfix"><div class="row-fluid">';

		if($item->max_selections > 0 && $item->max_selections >= $item->min_selections)
		{
			$range_selection = ' minselect="'.$item->min_selections.'" maxselect="'.$item->max_selections.'"';
		}
		
		foreach ($item->answers as $i=>$answer){
		
			$selected = false;
		
			if(!empty($item->responses)){
				
				foreach ($item->responses as $response){
						
					if($answer->id == $response->answer_id){
			
						$selected = true;
					} else if(!empty($response->free_text)){
			
						$free_text = CJFunctions::escape($response->free_text);
					}
				}
			}
						
			if($item->orientation == 'H'){

				$html .= '<div class="margin-bottom-20" style="overflow:auto;">';
					$html .= '<div class="form-inline margin-bottom-10">';
						$html .= '<label class="checkbox margin-top-10">';
							$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'" class="margin-right-10'.$range_selection.
								($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
							$html .= CJFunctions::escape($answer->answer_label);
						$html .= '</label>';
					$html .= '</div>';
					$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'" height="100" width="100">';
				$html .= '</div>';
			} else {

				$html .= '<div class="thumbnail span2 margin-bottom-20" style="overflow:auto;">';
					$html .= '<div class="center">';
						$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'" height="100" width="100">';
						$html .= '<div class="form-inline">';
							$html .= '<label class="checkbox margin-top-10">';
								$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'" class="margin-right-10'.$range_selection.
									($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
								$html .= CJFunctions::escape($answer->answer_label);
							$html .= '</label>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		
		$html .= '</div></div>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_user_name_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
		
		$names = explode('|', $free_text);
		
		if(count($names) != 3){

			$names = array('', '', '');
		}
		
		$html .= '<input type="text" name="user-name-'.$item->id.'[]" class="input-mini margin-right-10'.($item->mandatory ? ' required' : '').'" value="'.$names[0].'" placeholder="'.JText::_('LBL_NAME_TITLE').'">';
		$html .= '<input type="text" name="user-name-'.$item->id.'[]" class="input-medium margin-right-10'.($item->mandatory ? ' required' : '').'" value="'.$names[1].'" placeholder="'.JText::_('LBL_FIRST_NAME').'">';
		$html .= '<input type="text" name="user-name-'.$item->id.'[]" class="input-medium'.($item->mandatory ? ' required' : '').'" value="'.$names[2].'" placeholder="'.JText::_('LBL_LAST_NAME').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_user_email_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge email'.($item->mandatory ? ' required' : '').'" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_calendar_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}

		$html .= JHtml::_('calendar', 
					$free_text, 
					'free-text-'.$item->id, 
					'free-text-'.$item->id, 
					'%Y-%m-%d %H:%M:%S', 
					array('placeholder' => 'YYYY-MM-DD HH:mm:ss', 'class'=>'input-large date'.($item->mandatory ? ' required' : '')));
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_address_question($item, $class){

		$free_text = '';
		$country_options = array();
		$classname = $item->mandatory ? ' required' : '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.' clearfix">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.$item->description.'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
		
		$address_parts = explode('|||', $free_text);
		
		if(count($address_parts) != 7){
			
			$address_parts = array('', '', '', '', '', '', '');
		}
		
		if(null == $this->_countries){
			
			$this->_countries = CJFunctions::get_country_names();
		}
		
		foreach ($this->_countries as $country){
			
			$country_options[] = '<option value="'.$country->country_code.'"'.($address_parts[5] == $country->country_code ? ' selected="selected"' : '').'>'.
				CJFunctions::escape($country->country_name).'</option>';
		}
		
		$html .= '
		<div class="container-fluid address-wrapper error no-space-left">
			<div class="row-fluid">
				<input type="text" name="address-name-'.$item->id.'" class="span12'.$classname.'" value="'.$address_parts[0].'" 
					placeholder="'.JText::_('LBL_ADDRESS_FULL_NAME').'">
			</div>
			<div class="row-fluid">
				<input type="text" name="address-line1-'.$item->id.'" class="span12'.$classname.'" value="'.$address_parts[1].'" 
					placeholder="'.JText::_('LBL_ADDRESS_ADDRESS_LINE1').'">
			</div>
			<div class="row-fluid">
				<input type="text" name="address-line2-'.$item->id.'" class="span7" value="'.$address_parts[2].'" 
					placeholder="'.JText::_('LBL_ADDRESS_ADDRESS_LINE2').'">
				<input type="text" name="address-city-'.$item->id.'" class="span5'.$classname.' pull-right" value="'.$address_parts[3].'" 
					placeholder="'.JText::_('LBL_ADDRESS_ADDRESS_CITY').'">
			</div>
			<div class="row-fluid">
				<input type="text" name="address-state-'.$item->id.'" class="span4'.$classname.'" value="'.$address_parts[4].'" 
					placeholder="'.JText::_('LBL_ADDRESS_ADDRESS_STATE').'">
				<select name="address-country-'.$item->id.'" size="1" class="span5'.$classname.'">
					<option>'.JText::_('LBL_SELECT_OPTION').'</option>
					'.implode('', $country_options).'
				</select>
				<input type="text" name="address-zip-'.$item->id.'" class="span3'.$classname.' pull-right" value="'.$address_parts[6].'" 
					placeholder="'.JText::_('LBL_ADDRESS_ADDRESS_ZIP').'">
			</div>
		</div>';
		
		$html .= '</div>';
		
		return $html;
	}
}