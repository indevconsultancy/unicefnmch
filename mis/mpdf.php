<?php
require_once 'mpdfvendor/autoload.php';

$html = '
<style>
 
</style>
<h1>Welcome </h1>
<h3>English Text</h3>
<div style="font-family:freeserif">
    MQUAD is a mobile-based application system. It allows the research organizations primarily engaged in the development sector (knowledge-management, nutrition, health, education, governance, etc.) to conduct surveys & collect efficient data through electronic means. Further, present it using a generic visual layout and custom statistical widgets with ease.
    MQUAD digitizes forms using excel and mobile application interface to collect the quality data post-surveys. Subsequently, allows exporting the data in Excel sheet, Stata & SPSS for further analysis.
</div>
<h1>Hindi Text</h1>
<div style="font-family:freeserif">
    MQUAD एक मोबाइल-आधारित एप्लिकेशन प्रणाली है। यह मुख्य रूप से विकास क्षेत्र (ज्ञान-प्रबंधन, पोषण, स्वास्थ्य, शिक्षा, प्रशासन, आदि) में लगे अनुसंधान संगठनों को इलेक्ट्रॉनिक माध्यमों से सर्वेक्षण करने और कुशल डेटा एकत्र करने की अनुमति देता है। इसके अलावा, इसे सामान्य विज़ुअल लेआउट और कस्टम सांख्यिकीय विजेट का उपयोग करके आसानी से प्रस्तुत करें।
    सर्वेक्षण के बाद गुणवत्ता डेटा एकत्र करने के लिए MQUAD एक्सेल और मोबाइल एप्लिकेशन इंटरफ़ेस का उपयोग करके फॉर्मों को डिजिटाइज़ करता है। इसके बाद, आगे के विश्लेषण के लिए एक्सेल शीट, स्टेटा और एसपीएसएस में डेटा निर्यात करने की अनुमति देता है।
</div>
<h1>Gujarati Text</h1>
<div style="font-family:freeserif">
MQUAD એ મોબાઇલ-આધારિત એપ્લિકેશન સિસ્ટમ છે. તે મુખ્યત્વે વિકાસ ક્ષેત્રે સંકળાયેલી સંશોધન સંસ્થાઓને (જ્ઞાન-વ્યવસ્થાપન, પોષણ, આરોગ્ય, શિક્ષણ, શાસન વગેરે) સર્વેક્ષણો હાથ ધરવા અને ઈલેક્ટ્રોનિક માધ્યમથી કાર્યક્ષમ ડેટા એકત્રિત કરવાની મંજૂરી આપે છે. આગળ, તેને સામાન્ય વિઝ્યુઅલ લેઆઉટ અને કસ્ટમ આંકડાકીય વિજેટ્સનો ઉપયોગ કરીને સરળતા સાથે પ્રસ્તુત કરો.
MQUAD સર્વેક્ષણ પછી ગુણવત્તાયુક્ત ડેટા એકત્રિત કરવા માટે એક્સેલ અને મોબાઇલ એપ્લિકેશન ઇન્ટરફેસનો ઉપયોગ કરીને ફોર્મને ડિજિટાઇઝ કરે છે. ત્યારબાદ, વધુ વિશ્લેષણ માટે એક્સેલ શીટ, સ્ટેટા અને SPSS માં ડેટા નિકાસ કરવાની મંજૂરી આપે છે.
</div>
';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4-L',
    'orientation' => 'L'
]);

$mpdf->WriteHTML($html);

$filename = 'test.pdf';
$clientId='C91';
$file=$mpdf->Output($filename, \Mpdf\Output\Destination::FILE);

$movefile = file_put_contents("uploaded_questionnaire/pdf/" . $clientId . "/" . $filename, $file);
// Set headers to force download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filename));

// Output the PDF file
readfile($filename);
?>
