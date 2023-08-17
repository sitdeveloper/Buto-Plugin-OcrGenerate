<?php
/*
 * OCR generator.
 */
class PluginOcrGenerate{
  public $validator = array('success' => true, 'message' => null);
  /**
   * Generate ocr from a number.
   * @param integer $number A number where ocr should be added.
   * @param boolen $length If length number should be added.
   * @return integer
   */
  public function get($number, $length = false) {
    /**
     * Add length digit.
     */
    if($length){
      $number = $number.substr(wfPhpfunc::strlen($number)+2, -1);
    }
    /**
     * To array and reverse.
     */
    $number = str_split($number);
    $reverse = array_reverse($number);
    
    /**
     * Make every other double.
     */
    foreach ($reverse as $key => $value) {
      if ($key % 2 == 0) {
        $reverse[$key] = $value * 2;
      }
    }
    /*
     * Sum all.
     */
    $sum = 0;
    foreach ($reverse as $key => $value) {
      $plus = $value > 9 ? 1 : 0;
      $reverse[$key] = $value % 10 + $plus;
      $sum = $sum + $reverse[$key];
    }
    /**
     * Control.
     */
    $control = abs((ceil($sum / 10) * 10) - $sum);
    $ocr = implode($number);
    return $ocr . $control;
  }
  /**
   * Generate a OCR number.
   * Default length is 9 (or 10 if length=true) digits.
   * @param boolean $length
   * @param integer $min
   * @param integer $max
   * @return integer
   */
  public function generate($length = false, $min = 10000000, $max = 99999999){
    $number = rand($min, $max);
    return $this->get($number, $length);
  }
  /**
   * Validator.
   * Check for validator param for detalis.
   * @param integer $number
   * @param boolean $length
   * @return boolean
   */
  public function validate($number, $length = false){
    $clean = wfPhpfunc::substr($number, 0, wfPhpfunc::strlen($number)-1);
    $ocr = wfPhpfunc::substr($number, wfPhpfunc::strlen($number)-1);
    if($length){
      $length_digit = wfPhpfunc::substr($number, wfPhpfunc::strlen($number)-2, 1);
      if(wfPhpfunc::strlen($number)>9){
        if(wfPhpfunc::strlen($number) != 10+$length_digit){
          $this->validator = array('success' => false, 'message' => 'Length digit error.');
          return false;
        }
      }else{
        if(wfPhpfunc::strlen($number) != $length_digit){
          $this->validator = array('success' => false, 'message' => 'Length digit error.');
          return false;
        }
      }
    }
    if($this->get($clean) == $number){
      return true;
    }else{
      $this->validator = array('success' => false, 'message' => 'Last digit does not match.');
      return false;
    }
  }
}
