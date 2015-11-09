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

class PHP_Beautifier_Filter_ObjectOperatorAlign extends PHP_Beautifier_Filter {
    
    private $op_output_in_line = true;
    private $object_operator_count = 0;
    private $first_object_operator = false;
    /*
     * 每次检测到对象操作符，如果该行没有check过，需要对整行进行计算
     * 从对象操作符开始，往后计算命令长度，如果大于60，且命令操作符个数大于1，则换行输出。
     * 否则不换行
    */
    public function t_object_operator($sTag) {
        if ($this->object_operator_count == 0) {
            $this->op_output_in_line = true;
            $this->object_operator_count = 1;
            $this->first_object_operator = true;
            
            $iNext = 1;
            $rLength = 0;
            while (true) {
                $currentTag = $this->oBeaut->getNextTokenConstant($iNext++);
                $rLength+= strlen($currentTag);
                if ($currentTag == ";") {
                    if ($this->object_operator_count > 1 && $rLength > 60) {
                        $this->oBeaut->incIndent();
                        $this->oBeaut->incIndent();
                        $this->oBeaut->incIndent();
                        $this->op_output_in_line = false;
                    }
                    break;
                }
                if ($currentTag == T_OBJECT_OPERATOR) {
                    $this->object_operator_count++;
                }
            }
        }
        
        $this->object_operator_count--;
        
        if ($this->op_output_in_line) {
            $this->oBeaut->add($sTag);
        } 
        else {
            if (!$this->first_object_operator) {
                $this->oBeaut->addNewLine();
                $this->oBeaut->addIndent();
            } 
            else {
                $this->first_object_operator = false;
            }
            $this->oBeaut->add($sTag);
            if ($this->object_operator_count == 0) {
                $this->op_output_in_line = true;
                $this->oBeaut->decIndent();
                $this->oBeaut->decIndent();
                $this->oBeaut->decIndent();
            }
        }
    }
}
