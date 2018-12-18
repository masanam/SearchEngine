<?php
/**
 * @version		$Id: qnresults.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class QuizQuestionResults 
{
	private $_wysiwyg = false;
	private $_bbcode = false;
	private $_content = false;
	private $_score = 0;
	private $_total = 0;
	private $_correct = 0;
	private $_count = 0;
	
	function __construct($wysiwyg, $bbcode, $content)
	{
		$this->_wysiwyg = $wysiwyg;
		$this->_bbcode = $bbcode;
		$this->_content = $content;
	}
	
	public function get_score()
	{
		return $this->_score;
	}
	
	public function get_count()
	{
		return $this->_count;
	}

	public function get_total()
	{
		return $this->_total;
	}
	
	public function get_percentage()
	{
		return $this->_total > 0 ? round($this->_score * 100 / $this->_total, 2) : 0;
	}

	public function get_success_ratio()
	{
		return $this->_count > 0 ? round($this->_correct * 100 / $this->_count, 2) : 0;
	}
	
	public function get_page_header_question($item, $class)
	{
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::process_html($item->title, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '</div>';
		return $html;
	}
	
	public function get_choice_question($item, $class)
	{
		$free_text = '';
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::process_html($item->title, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="answers margin-bottom-20">';
		$correct_answer = true;
		$icon = $item->question_type == 3 ? 'fa fa-square-o' : 'fa fa-circle-o';
		
		foreach ($item->answers as $answer)
		{
			$selected = false;
			$correct_not_selected = false;
			
			foreach ($item->responses as $response)
			{
				if($response->answer_id == $answer->id)
				{
					$selected = true;
				} 
				elseif(!empty($response->free_text))
				{
					$free_text = $response->free_text;
				}
			}
			
			if(!$selected && $answer->correct_answer > 0) 
			{
				$correct_not_selected = true;
			}
			
			$html .= '<div class="form-inline report-answers">';
			
			if($selected)
			{
				$html .= '<i class="fa fa-hand-o-up tooltip-hover" title="'.JText::_('LBL_YOUR_ANSWER').'"></i>';
			} 
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i>';
			}
			
			if($answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-check tooltip-hover" title="'.JText::_('LBL_CORRECT_ANSWER').'"></i>';
				$this->_total = $this->_total + $answer->marks;
			} 
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i>';
			}
			
			if($selected && $answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-check-square-o tooltip-hover" title="'.JText::_('LBL_SELECTED_CORRECT').'"></i>';
				$this->_score = $this->_score + $answer->marks;
			} 
			else if($selected && $answer->correct_answer == 0)
			{
				$html .= '<i class="fa fa-minus-square tooltip-hover" title="'.JText::_('LBL_SELECTED_WRONG').'"></i>';
				$correct_answer = false;
			} 
			else if(!$selected && $answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-thumbs-down tooltip-hover" title="'.JText::_('LBL_NOT_SELECTED_CORRECT').'"></i>';
				$correct_answer = false;
			} 
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i>';
			}
			
			$html .= CJFunctions::escape($answer->title);
			$html .= '</div>';
		}
		
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		$html .= '</div>';
		
		if($correct_answer)
		{
			$html .= '<div class="margin-bottom-10 text-success"><i class="fa fa-check-square-o fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_CORRECTLY').'</div>';
			$this->_correct++;
		} 
		else 
		{
			$html .= '<div class="margin-bottom-10 text-error"><i class="fa fa-minus-square fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_WRONGLY').'</div>';
		}
		
		$explanation = strip_tags($item->answer_explanation);
		
		if(!empty($explanation))
		{
			$html .= '<h4 class="page-header margin-bottom-10">'.JText::_('LBL_ANSWER_EXPLANATION').'</h4>';
			$html .= '<div>'.CJFunctions::process_html($item->answer_explanation, $this->_bbcode, $this->_content).'</div>';
		}
		
		$html .= '</div>';
		$this->_count++;
		
		return $html;
	}
	
	public function get_grid_question($item, $class)
	{
		$free_text = '';
		$item->columns = array();
		$correct_answer = true;
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::process_html($item->title, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="answers margin-bottom-20">';
		$icon = $item->question_type == 6 ? 'fa fa-square-o' : 'fa fa-circle-o';
		
		$html .= '<table class="table table-bordered table-hover table-striped margin-top-20">';
		$html .= '<thead><tr><td></td>';
		
		foreach($item->answers as $answer)
		{
			if($answer->answer_type == 'y')
			{
				$html .= '<td class="center">'.CJFunctions::escape($answer->title).'</td>';
				$item->columns[] = $answer;
			}
		}
		
		$html .= '</tr><thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $answer)
		{
			if($answer->answer_type == 'x')
			{
				$html .= '<tr>';
				$html .= '<td>'.CJFunctions::escape($answer->title).'</td>';
				
				foreach($item->columns as $column)
				{
					$selected = false;
					foreach ($item->responses as $response)
					{
						if($response->answer_id == $answer->id && $response->column_id == $column->id)
						{
							$selected = true;
						} 
						else if(!empty($response->free_text))
						{
							$free_text = $response->free_text;
						}
					}
					
					if($answer->correct_answer == $column->id)
					{
						if($selected)
						{
							$html .= '<td class="center text-center"><i class="fa fa-check-square-o tooltip-hover" title="'.JText::_('LBL_SELECTED_CORRECT').'"></i></td>';
							$this->_score = $this->_score + $answer->marks;
						} 
						else 
						{
							$correct_answer = false;
							$html .= '<td class="center text-center"><i class="fa fa-thumbs-down tooltip-hover" title="'.JText::_('LBL_NOT_SELECTED_CORRECT').'"></i></td>';
						}
						
						$this->_total = $this->_total + $answer->marks;
					} 
					else if($selected)
					{
						$correct_answer = false;
						$html .= '<td class="center text-center"><i class="fa fa-minus-square tooltip-hover" title="'.JText::_('LBL_SELECTED_WRONG').'"></i></td>';
					} 
					else 
					{
						$html .= '<td class="center text-center"><i class="'.$icon.' muted"></i></td>';
					}
				}
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		$html .= '</div>';
		
		if($correct_answer)
		{
			$html .= '<div class="margin-bottom-10 text-success"><i class="fa fa-check-square-o fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_CORRECTLY').'</div>';
			$this->_correct++;
		} 
		else 
		{
			$html .= '<div class="margin-bottom-10 text-error"><i class="fa fa-minus-square fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_WRONGLY').'</div>';
		}
		
		$explanation = strip_tags($item->answer_explanation);
		
		if(!empty($explanation))
		{
			$html .= '<h4 class="page-header margin-bottom-10">'.JText::_('LBL_ANSWER_EXPLANATION').'</h4>';
			$html .= '<div>'.CJFunctions::process_html($item->answer_explanation, $this->_bbcode, $this->_content).'</div>';
		}
		
		$html .= '</div>';
		$this->_count++;
		
		return $html;
	}

	public function get_text_question($item, $class, $escape = true)
	{
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::process_html($item->title, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		foreach ($item->responses as $response)
		{
			if(!empty($response->free_text))
			{
				$html .= '<h3>'.JText::_('LBL_ANSWER').'</h2>';
				$html .= '<div>'.($escape ? CJFunctions::escape($response->free_text) : $response->free_text).'</div>';
			}
		}
		
		$html .= '</div>';
	
		return $html;
	}
	
	public function get_image_question($item, $class, $base_uri)
	{
		$free_text = '';
		$correct_answer = true;
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::process_html($item->title, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '<div class="answers margin-bottom-20 margin-top-20">';
		$icon = $item->question_type == 12 ? 'fa fa-square-o' : 'fa fa-circle-o';
		
		foreach ($item->answers as $i=>$answer)
		{
			$selected = false;
			
			foreach ($item->responses as $response)
			{
				if($response->answer_id == $answer->id)
				{
					$selected = true;
				} 
				else if(!empty($response->free_text))
				{
					$free_text = $response->free_text;
				}
			}
			
			if($i % 6 == 0) $html .= '<div class="row-fluid">';
			
			$html .= '<div class="thumbnail span2 margin-bottom-20">';
			$html .= '<div class="center text-center">';
			$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->title).'">';
			$html .= '<div class="form-inline margin-top-10">';

			if($selected)
			{
				$html .= '<i class="fa fa-hand-o-up tooltip-hover" title="'.JText::_('LBL_YOUR_ANSWER').'"></i> ';
			} 
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i> ';
			}
			
			if($answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-check tooltip-hover" title="'.JText::_('LBL_CORRECT_ANSWER').'"></i> ';
				$this->_total = $this->_total + $answer->marks;
			}
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i> ';
			}
			
			if($selected && $answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-check-square-o tooltip-hover" title="'.JText::_('LBL_SELECTED_CORRECT').'"></i> ';
				$this->_score = $this->_score + $answer->marks;
			} 
			else if($selected && $answer->correct_answer == 0)
			{
				$html .= '<i class="fa fa-minus-square tooltip-hover" title="'.JText::_('LBL_SELECTED_WRONG').'"></i> ';
				$correct_answer = false;
			} 
			else if(!$selected && $answer->correct_answer > 0)
			{
				$html .= '<i class="fa fa-thumbs-down tooltip-hover" title="'.JText::_('LBL_NOT_SELECTED_CORRECT').'"></i> ';
				$correct_answer = false;
			} 
			else 
			{
				$html .= '<i class="'.$icon.' muted"></i> ';
			}
			$html .= CJFunctions::escape($answer->title);
				
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			
			if($i % 6 == 5 || count($item->answers) == ($i + 1)) $html .= '</div>';
		}
		
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		$html .= '</div>';
		
		if($correct_answer)
		{
			$html .= '<div class="margin-bottom-10 text-success"><i class="fa fa-check-square-o fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_CORRECTLY').'</div>';
			$this->_correct++;
		} 
		else 
		{
			$html .= '<div class="margin-bottom-10 text-error"><i class="fa fa-minus-square fa fa-large"></i> '.JText::_('TXT_QUESTION_ANSWERED_WRONGLY').'</div>';
		}
		
		$explanation = strip_tags($item->answer_explanation);
		
		if(!empty($explanation))
		{
			$html .= '<h4 class="page-header margin-bottom-10">'.JText::_('LBL_ANSWER_EXPLANATION').'</h4>';
			$html .= '<div>'.CJFunctions::process_html($item->answer_explanation, $this->_bbcode, $this->_content).'</div>';
		}

		$html .= '</div>';
		$this->_count++;
		
		return $html;
	}

	public function get_correct(){
		return $this->_correct;
	}
}