<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Space in Square: Add padding spaces within square brackets if contains a variable, ie. [ $a ],["key"],[]
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     Davide Pedone <davide.pedone@gmail.com>
 * @copyright  2014 Davide Pedone
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 *
 */

class PHP_Beautifier_Filter_SpaceInSquare extends PHP_Beautifier_Filter {
    
    private $square_count = 0;
    private $output_in_line = false;
    
    /**
     * t_open_square_brace
     * 每个开中括号都需开启一个判定条件while
     * 如果到下一个中括号的之间的长度大于80，则按换行开始
     * 如果小于80，则全部添加到一行
     *
     * @param mixed $sTag The tag to be procesed
     *
     * @access public
     * @return void
     *
     */
    
    public function t_open_square_brace($sTag) {
        
        if (!$this->oBeaut->isNextTokenConstant(T_CLOSE_SQUARE_BRACE) && $this->oBeaut->isNextTokenConstant(T_VARIABLE)) {
            $sTag = $sTag . ' ';
        }
        
        //如果当前括号输入output_in_line的范围，则不再重复计算直接输出
        if (!$this->output_in_line) {
            $index = 1;
            $strlen_in_square = 0;
            $square_count_temp = 1;
            $this->square_count = 1;
            
            //while i计数，终止条件是中间的字符串长度总和大于80，或者在长度小于80时遇到闭括号
            while ($strlen_in_square <= 40) {
                if ($this->oBeaut->isNextTokenConstant('[',
                $index)) {
                    $square_count_temp++;
                    $this->square_count++;
                }
                if ($this->oBeaut->isNextTokenConstant(']',
                $index)) {
                    $square_count_temp--;
                    if ($square_count_temp == 0) {
                        $this->output_in_line = true;
                        break;
                    }
                }
                $strlen_in_square+= strlen($this->oBeaut->getNextTokenContent($index++));
            }
        }
        
        //设置一个变量，如果该变量为真，将square中的变量输入一行
        if ($this->output_in_line) {
            $this->oBeaut->add($sTag);
        } 
        else {
            $this->oBeaut->add($sTag);
            $this->oBeaut->addNewLine();
            $this->oBeaut->incIndent();
            $this->oBeaut->addIndent();
        }
    }
    
    /**
     * t_close_square_brace
     *
     * @param mixed $sTag The tag to be procesed
     *
     * @access public
     * @return void
     */
    
    public function t_close_square_brace($sTag) {
        
        if (!$this->oBeaut->isPreviousTokenConstant(T_OPEN_SQUARE_BRACE) && $this->oBeaut->isPreviousTokenConstant(T_VARIABLE)) {
            $sTag = ' ' . $sTag;
        }
        
        $this->square_count--;
        
        if ($this->output_in_line) {
            $this->oBeaut->add($sTag);
        } 
        else {
            $this->oBeaut->addNewLine();
            $this->oBeaut->decIndent();
            $this->oBeaut->addIndent();
            $this->oBeaut->add($sTag);
        }
        
        if ($this->output_in_line) {
            if ($this->square_count == 0) {
                $this->output_in_line = false;
            }
        }
    }
    
    public function t_comma($sTag) {
        if ($this->output_in_line) {
            $this->oBeaut->add($sTag);
        } 
        else {
            $this->oBeaut->add($sTag);
            if (!$this->oBeaut->isNextTokenConstant(']')) {
                $this->oBeaut->addNewLine();
                $this->oBeaut->addIndent();
            }
        }
    }
}
