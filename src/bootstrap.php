<?php

use GuzzleHttp\Psr7\ServerRequest; //المسؤول عن المدخلات - التعامل مع طلبات المستخدم
use GuzzleHttp\Psr7\Response; // المسؤول عن المخرجات - الارسال للمستخدم
use GuzzleHttp\Psr7\Utils; // ادوات مساعده - Stream
use HttpSoft\Emitter\SapiEmitter; //لارسال الاستجابه كامله دفعه واحده

//--- اظهار واخفاء رساله الخطا للمستخدم
//--- نظع الرقم واحد للظهور والرقم صفر للاخفاء
ini_set("display_errors", 1); // تعديل لاحقا

//---ServerRequest---
// "يقوم بتحميل ملف autoload.php الخاص بي Composer
//  وهو المسؤول عن استدعاء جميع المكتبات الخارجية التي قمت بتثبيتها تلقائي"
require dirname(__DIR__) . "/vendor/autoload.php"; 

// "إنشاء Object يمثل طلب المتصفح HTTP Request
//  ويجمع البيانات من المصفوفات العالمية مثل $_GET و $_POST و $_SERVER 
// ويضعها في متغير واحد منظم يسهل التعامل معه"
$request = ServerRequest::fromGlobals(); 
//---

//---The Router---
//A1
$path = $request->getUri()->getPath() ; // $_SERVER['REQUEST_URI']

// $page = $request->getQueryParams()["page"]; // = $_GET["page"];
$page = match ($path) {
    "/"     => "home",
    "/home" => "home"
};
//---

//---ob_start() - /public/{$page}.php---
// ---ob_start()/
// "الوظيفة  تخبر PHP أي نص أو كود HTML سيتم طباعته بعد الآن،
//  لا ترسله للمتصفح، بل احفظه في ذاكرة مؤقتة عندي"
// ---
ob_start();

// ---
// "استدعاء ملف صفحة يحتوي على كود HTML وبسبب ob_start لن يظهر أي شيء للمستخدم بعد."
// ---
require dirname(__DIR__)  . "/public/{$page}.php";

// ---
// "تأخذ كل كودHTML  الذي تم حبسه وتخزيه بشكل مؤقت وتضعه في المتغير $content. 
// تقوم بإيقاف Buffering وتنظيف الخزان."
// ---
$content = ob_get_clean();

//---

//---Stream---
// انشاء متغبر من نوع Stream لتزين محتوى الارسال  واللي هو الصفحه
// --- [ شرح دالة Stream ]
// الوظيفة: 
// "تحويل نص عادي إلى Stream تدفق بيانات 
// مكتبات PSR-7 لا تتعامل مع النصوص مباشرة داخل محتوى الاستجابة 
// بل كتدفقات لتوفير الذاكرة عند التعامل مع ملفات كبيرة 
//
// المدخلات: 
// سلسلة نصية String، أو مورد ملف Resource، أو مسار ملف "" \
// ---
$stream = Utils::streamFor($content);
//---

//---Response---

// انشاء كان للارسال الي المستخدم- انشاء استجابه فارغه للارسال
$response = new Response();

// تهيئه رسال استجايه response
// ---HTTP Body
// المنطقه التي تخزن فيها البيانات في طلب HTTP Messages
// هنا نحنا نحط معلومات الاستجابه
// ---
$response = $response->withStatus(418) 
                    ->withHeader("X-Powered-By","PHP")                                                                            
                    ->withBody($stream);
//_

// ---SapiEmitter
//"بدل من ارسال الاستجايه على شكل اجزاء منفصله
//  مثلا ارسال جزء البدي  بشكل منفصل echo $response->getBody()
//  نفوم هنا بارساله دفعه واحده"
// ---
$emitter = new SapiEmitter();
$emitter->emit($response); 
//---



