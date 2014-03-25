<?php

/*
	yoypdf.php
	2013/10/22- (c) yoya@awm.jp
*/

require('rotation_japanese.php');

class YoyaPDF extends PDF_Rotate
{
    function RotatedText($x,$y,$txt,$angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
    
    function RotatedImage($file,$x,$y,$w,$h,$angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
    function SetFont($name, $something, $size)
    {
        parent::SetFont($name, $something, $size);
        $this->fontSize = $size;
    }
    function TategakiText($x, $y, $txt, $m)
    {
        foreach (preg_split('/(?<!^)(?!$)/u', $txt) as $c) {
            $fontSize = $this->fontSize / 7.2;

            if ((ord(substr($c, 0, 1)) & 0x80) === 0) { // UTF-8
                $this->Rotate(-90, $x+$fontSize*1.1, $y-$fontSize*1.1);
                $this->Text($x , $y - $fontSize * 0.5, $c);
                $this->Rotate(0);
                $y += $this->GetStringWidth($c) + $fontSize*0.2;
            } else {
                switch ($c) {
                  case 'ー':
                    $this->Rotate(90, $x+$fontSize*1.1, $y-$fontSize*1.1, 1);
                    $this->Text($x, $y, $c);
                    $this->Rotate(0);
                    break;
                  case '－':
                  case '―':
                  case '‐':
                  case '～':
                    $this->Rotate(90, $x+$fontSize*1.1, $y-$fontSize*1.1);
                    $this->Text($x, $y, $c);
                    $this->Rotate(0);
                    break;
                  case '、':
                  case '。':
                  case '，':
                  case '．':
                    $this->Text($x + $fontSize*1.5, $y - $fontSize*2, $c);
                     break;
                  case '」':
                  case '”':
                    $this->Text($x + $fontSize*1.5, $y, $c);
                     break;
                  default:
                    $this->Text($x, $y, $c);
                    break;
                }
                $y += $m;
                continue;
            }
        }
    }
}
