<?php

use App\Http\Controllers\AddETypeRegistrationNumber;
use App\Http\Controllers\AllAprovedApplication;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantDetailController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerAuth\ForgotPasswordSMSController;
use App\Http\Controllers\CustomerAuth\LoginController;
use App\Http\Controllers\CustomerAuth\RegisterController;
use App\Http\Controllers\DriverInfoController;
use App\Http\Controllers\ETypeApplicationController;
use App\Http\Controllers\ExComanderController;
use App\Http\Controllers\ExSsoController;
use App\Http\Controllers\FixedFileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HeaderFooterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomePageComtroller;
use App\Http\Controllers\ImportentLinkController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfFileController;
use App\Http\Controllers\PhotoGalleryController;
use App\Http\Controllers\PresentCommanderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SloganController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SSlComarzController;
use App\Http\Controllers\StickerPrintController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleInfoController;
use App\Http\Controllers\VehicleStickerController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/clear_cache', function () {
	Artisan::call('route:clear');
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	Artisan::call('storage:link');
	return 'Clear Cache';
});

Route::get('/set-language/{lang}', function ($lang) {
	if (!in_array($lang, ['en', 'bn'])) {
		$lang = 'en'; // default
	}
	session(['lang' => $lang]);
	return redirect()->back();
})->name('set-language');

Route::get('/rhythm', [HomeController::class, 'rhythm']);
// front-pages
Route::get('/', [FrontController::class, 'index']);
Route::get('/about-station-headquarters', [FrontController::class, 'about'])->name('about');
Route::get('/single_major/{id}/{name}', [FrontController::class, 'major'])->name('major');
Route::get('/Commender/details', [FrontController::class, 'commender'])->name('commender_details');
Route::get('/policy', [FrontController::class, 'policy'])->name('policy');
Route::get('/graveyard', [FrontController::class, 'graveyard'])->name('graveyard');
Route::get('/bus', [FrontController::class, 'bus'])->name('bus');
Route::get('/private', [FrontController::class, 'private'])->name('private');
Route::get('/anti-malaria-drive', [FrontController::class, 'anti_malaria_drive'])->name('anti_malaria_drive');
Route::get('/drainage-and-cleaning', [FrontController::class, 'drainage_and_cleaning'])->name('drainage_and_cleaning');
Route::get('/disposal-of-garbage', [FrontController::class, 'disposal_of_garbage'])->name('disposal_of_garbage');
Route::get('/mp-checkpost', [FrontController::class, 'mp_checkpost'])->name('mp_checkpost');
Route::get('/shuttle-service', [FrontController::class, 'shuttle_service'])->name('shuttle_service');
Route::get('/other-services', [FrontController::class, 'other_services'])->name('other_services');
Route::get('/gate-on-of-time', [FrontController::class, 'gate_on_of_time'])->name('gate_on_of_time');
Route::get('/contact-info', [FrontController::class, 'contact_info'])->name('contact_info');
Route::get('/helpline', [FrontController::class, 'helpline'])->name('helpline');
Route::get('/notice-details', function () {
	return redirect('/');
})->name('notice_details');
Route::get('/notice-details/{slug}', [FrontController::class, 'noticeDetails'])->name('notice_details_slug');
Route::get('/policy&form', [FrontController::class, 'policyForms'])->name('policy_forms');

// form routes
Route::get('/all-notice', [FrontController::class, 'notice'])->name('all-notice');

Route::get('/sticker-notice', function () {
	return view('front-pages.sticker-notice');
});
Route::get('/tender-foot-over-bridge', function () {
	return view('front-pages.tender-foot-over-bridge');
});
Route::get('/tender-lighting-gate-signal', function () {
	return view('front-pages.tender-lighting-gate-signal');
});
Route::get('/tender-notice', function () {
	return view('front-pages.tender-notice');
});



// front-pages ended
Route::group(['middleware' => 'prevent_back_history'], function () {
	Route::get('/application/edit/applicant/{appNumber}', [ApplicationController::class, 'applicationEditApplicant'])->middleware('applicant');
	Route::get('/application/view/applicant/{appNumber}', [ApplicationController::class, 'viewApplication'])->middleware('applicant')->name('application.view');

	// Route::get('/application/view/applicant/{appNumber}', 'ApplicationController@viewApplication')->middleware('applicant');
	Route::get('/renew/sticker/{stickerID}', [VehicleStickerController::class, 'renewRequest'])->middleware('applicant');
	Route::post('/applicant-details/store', [ApplicantDetailController::class, 'storeApplicantDetails'])->middleware('applicant');
	Route::post('/vehicle-detail/store', [VehicleInfoController::class, 'storeVehicleDetails'])->middleware('applicant');
	Route::post('/driver-details/store', [DriverInfoController::class, 'storeDriverDetails'])->middleware('applicant');
	Route::post('/document/store', [DriverInfoController::class, 'storeDocuments'])->middleware('applicant');
	Route::post('/send-submission-sms/success/{id}', [ApplicationController::class, 'submissionSmsSend'])->middleware('applicant');
	Route::post('/send-submission-renew-sms/success/{id}', [ApplicationController::class, 'submissionSmsSendRenew'])->middleware('applicant');
	Route::get('/applied-applications', [ApplicantController::class, 'showAppliedForms'])->middleware('applicant');
	Route::get('/alocated-stickers', function () {
		return view('layouts.alocated-sticker');
	})->middleware('applicant');
	Route::get('/app/{id}/invoice', [ApplicantController::class, 'invReceipt'])
		->middleware('applicant')
		->name('app.invoice');
	Route::get('/about/customer', function () {
		return view('layouts.about-customer');
	})->middleware('applicant');
	Route::get('/customer/home', [ApplicantController::class, 'applyForm'])->middleware('applicant');
	Route::post('/applicant-detail/update/{app_id}', [ApplicantDetailController::class, 'updateApplicantDetail'])->middleware('anyauth');
	Route::post('/vehicle-detail/update/{app_id}', [VehicleInfoController::class, 'vehicleInfoUpdate'])->middleware('anyauth');
	Route::post('/driver-detail/update/{app_id}', [DriverInfoController::class, 'driverInfoUpdate'])->middleware('anyauth');
	Route::post('/document/update/{app_id}', [DriverInfoController::class, 'updateDocument'])->middleware('anyauth');
	Route::get('/home', [HomeController::class, 'index'])->name('home');
	Route::get('/application/pending/{def}', [HomeController::class, 'pendingApp'])->name('pendingApp');
	Route::get('/application/hold/{def}', [HomeController::class, 'holdingApp'])->name('holdingApp');
	Route::get('/application/retake/{def}', [HomeController::class, 'retakeApp'])->name('retakeApp');
	// all pending datatable
	Route::get('all-pending/datatable', [HomeController::class, 'allPendingDatatable'])->name('all_pending.datatable');

	// all holding datatable
	Route::get('all-holding/datatable', [HomeController::class, 'allHoldingDatatable'])->name('all_holding.datatable');
	// all pending datatable
	Route::get('all-retake/datatable', [HomeController::class, 'allRetakeDatatable'])->name('all_retake.datatable');


	// all approved datatable
	Route::get('all-approved/datatable', [HomeController::class, 'allApprovedDatatable'])->name('all_approved.datatable');
	// all delevered datatable
	Route::get('all-free_approved/datatable', [HomeController::class, 'allFreeApprovedDatatable'])->name('all_free_approved.datatable');
	Route::get('all-delevered/datatable', [HomeController::class, 'allDeleveredDatatable'])->name('all_delevered.datatable');
	// all rejected datatable
	Route::get('all-rejected/datatable', [HomeController::class, 'allRejectedDatatable'])->name('all_rejected.datatable');
	// all expired datatable
	Route::get('all-expired/datatable', [HomeController::class, 'allExpiredDatatable'])->name('all_expired.datatable');
	// approved datatable
	Route::get('approved_apps/datatable', [HomeController::class, 'approvedAppsDatatable'])->name('approved_apps.datatable');
	// issued datatable
	Route::get('issued_apps/datatable', [HomeController::class, 'issuedAppsDatatable'])->name('issued_apps.datatable');
	//Route::get('approved_apps/datatable', [HomeController::class, 'approvedAppsDatatable'])->name('approved_apps.datatable');
	// All Apps datatable
	Route::get('all_apps/datatable', [HomeController::class, 'allAppsDatatable'])->name('all_apps.datatable');
	// Forwared datatable
	Route::get('forwarded_apps/datatable', [HomeController::class, 'forwardedAppsDatatable'])->name('forwarded_apps.datatable');
	// Invoice datatable
	Route::get('invoice/datatable', [InvoiceController::class, 'invoiceDatatable'])->name('invoice.datatable');

	Route::get('/application/approved/{def}', [HomeController::class, 'approvedApp'])->name('approvedApp');
	Route::get('/application/free_approved/{def}', [HomeController::class, 'freeApprovedApp'])->name('freeApprovedApp');
	Route::get('/application/delivered/{def}', [HomeController::class, 'deliveredApp'])->name('deliveredApp');
	Route::get('/application/rejected/{def}', [HomeController::class, 'rejectedApp'])->name('rejectedApp');
	Route::get('/sticker/expired/{def}', [HomeController::class, 'expiredSticker'])->name('expiredSticker');

	Route::get('/application-review/{app_number}', [HomeController::class, 'applicationReview'])->name('application.review');
	Route::post('/application/approve', [HomeController::class, 'applicationApprove']);
	Route::get('/application/reject', [HomeController::class, 'applicationReject']);
	Route::get('/application/edit/{appNumber}', [HomeController::class, 'applicationEdit']);
	Route::get('/application/delete', [HomeController::class, 'applicationDelete']);
	Route::get('/application/forward', [HomeController::class, 'applicationForward']);
	Route::get('/application/forwarded-list', [HomeController::class, 'applicationForwardedList']);
	Route::post('/admin-search', [UserController::class, 'adminSearch']);
	Route::post('/sticker/issue', [HomeController::class, 'issueSticker']);
	Route::get('/def/sticker/expired', [HomeController::class, 'expiredSticker']);
	Route::post('/all-applications', [HomeController::class, 'allStickerApplications']);

	// sticker print
	Route::get('/sticker-print/{app_number}', [StickerPrintController::class, 'index'])->name('sticker.print');
	Route::get('/all-sticker-print', [StickerPrintController::class, 'printAllStickers'])->name('sticker.printAll');
	Route::post('/all-sticker-set-date', [StickerPrintController::class, 'setAllStickerPrintDate'])->name('sticker.printAll_set_date');
	// / Qr code generate route
	// routes/web.php
	Route::get('/qr/{app_number}',  [StickerPrintController::class, 'showByQr'])->name('application.qr');
	// Route::get('/application/qr/{app_number}',  [StickerPrintController::class, 'showByQr'])->name('application.qr');

	Route::get('/invoice/{id}', [InvoiceController::class, 'printInvoice']);
	Route::get('/invoice-list', [InvoiceController::class, 'allInvoice']);
	Route::get('/invoice-report', [InvoiceController::class, 'invoiceReport']);
	Route::post('/search/invoice', [InvoiceController::class, 'searchInvoice']);
	Route::post('/search/invoice/report', [InvoiceController::class, 'searchInvoiceReport']);
	Route::get('/applications/{id}/invoice', [InvoiceController::class, 'invoiceReceipt'])->name('applications.invoice');


	Route::get(
		'/application-review-from-notfication/{app_number}/{not_id}',
		[HomeController::class, 'applicationRevewFromNotification']
	);
	Route::post('/send/sms', [HomeController::class, 'notify']);
	Route::get('/bank-deposit', [HomeController::class, 'bankDeposit']);
	Route::get('/application/undo', [HomeController::class, 'undoApplication']);
	Route::post('/application/hold', [HomeController::class, 'HoldApplication']);
	Route::post('/add/bank/deposit', [HomeController::class, 'storeBankDeposit']);
	Route::get('/delivery-report', [HomeController::class, 'deliveryReport']);
	Route::get('/all-approved/application', [HomeController::class, 'allApproveApps']);
	Route::post('/search/sticker/report', [HomeController::class, 'searchStickerReport']);
	Route::post('/search/inspection', [HomeController::class, 'searchInspecList']);
	Route::get('/admin-list', [HomeController::class, 'adminsList'])->middleware('super-admin');
	Route::post('/add/admin', [HomeController::class, 'addAdmin'])->middleware('super-admin');
	Route::post('update/admin/{id}', [HomeController::class, 'updateAdmin'])->middleware('super-admin');
	Route::post('/delete/admin', [HomeController::class, 'deleteAdmin'])->middleware('super-admin');
	Route::get('/sticker-list', [HomeController::class, 'stickerTypes'])->middleware('super-admin');
	Route::post('/add/sticker', [HomeController::class, 'addSticker'])->middleware('super-admin');
	Route::post('/update/sticker/{id}', [HomeController::class, 'updateSticker'])->middleware('super-admin');
	Route::post('/delete/sticker', [HomeController::class, 'deleteSticker'])->middleware('super-admin');
	Route::get('/sms-panel', [SmsController::class, 'smsPanel']);
	Route::post('/add/sms', [SmsController::class, 'smsAdd']);
	Route::post('/update/sms/{id}', [SmsController::class, 'smsUpdate']);
	Route::post('/delete/sms', [SmsController::class, 'smsDelete']);
	Route::get('/send-sms/retry/{id}', [SmsController::class, 'retrySendSms'])->middleware('auth');
	Route::post('/change/password', [UserController::class, 'changePassword'])->middleware('auth');
	Route::get('/customer-list', [UserController::class, 'clientsList'])->middleware('auth');
	Route::post('/update/client/{clientId}', [UserController::class, 'updateClient'])->middleware('auth');
	Route::post('/search/forwarded-list', [HomeController::class, 'searchForwardedList'])->middleware('auth');
	Route::post('/search/issued', [HomeController::class, 'searchIssuedList'])->middleware('auth');
	Route::get('/all-issued/application', [HomeController::class, 'allIssuedList'])->middleware('auth');
	Route::post('/fetch_clients_records', [HomeController::class, 'DataTableClientFetch'])->middleware('auth');
	Route::post('/fetch_apps_records', [UserController::class, 'DataTableAppsFetch'])->middleware('auth');
	Route::post('/fetch_issued_apps_records', [HomeController::class, 'DataTableIssuedAppsFetch'])->middleware('auth')->name('fetch_issued_apps_records');
	Route::get('/home/allapps', [HomeController::class, 'allapps'])->middleware('auth');
	Route::get('/issued/allapps', [HomeController::class, 'allIssuedList'])->middleware('auth');
	Route::get('/send-queued-sms/{queue_status}', [SmsController::class, 'sendQueueSms'])->middleware('auth');

	Route::post('admin/resent-schedule-sms', [ApplicationController::class, 'resentScheduleSms'])->middleware('super-admin')->name('resent_schedule_sms');

	// Website Related Routs
	Route::get('header_footer', [HeaderFooterController::class, 'headerFooter'])->name('header_footer')->middleware('super-admin');
	Route::post('header_footer', [HeaderFooterController::class, 'headerFooterStore'])->name('header_footer_store')->middleware('super-admin');


	// Website Notice
	Route::get('notices', [NoticeController::class, 'index'])->name('website_notice')->middleware('super-admin');
	Route::get('notices/add', [NoticeController::class, 'add'])->name('website_notice.add')->middleware('super-admin');
	Route::post('notices/add', [NoticeController::class, 'addPost'])->middleware('super-admin');

	Route::get('notices/edit/{notice}', [NoticeController::class, 'edit'])->name('website_notice.edit')->middleware('super-admin');
	Route::post('notices/edit/{notice}', [NoticeController::class, 'editPost'])->middleware('super-admin');
	Route::get('notices/delete/{id}', [NoticeController::class, 'delete'])->name('website_notice.delete')->middleware('super-admin');


	// Slider Routes
	Route::get('slider/list', [SliderController::class, 'list'])->name('slider.list')->middleware('super-admin');
	Route::get('slider/add', [SliderController::class, 'add'])->name('slider.add')->middleware('super-admin');
	Route::post('slider/add', [SliderController::class, 'store'])->name('slider.store')->middleware('super-admin');
	Route::get('slider/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit')->middleware('super-admin');
	Route::post('slider/update/{id}', [SliderController::class, 'update'])->name('slider.update')->middleware('super-admin');
	Route::get('slider/delete/{id}', [SliderController::class, 'delete'])->name('slider.delete')->middleware('super-admin');
	// Present Commander Routes
	Route::get('present_commander/add', [PresentCommanderController::class, 'add'])->name('present_commander.add')->middleware('super-admin');
	Route::post('present_commander/add', [PresentCommanderController::class, 'store'])->name('present_commander.store')->middleware('super-admin');
	// Major Routes
	Route::get('major/list', [MajorController::class, 'list'])->name('major.list')->middleware('super-admin');
	Route::get('major/add', [MajorController::class, 'add'])->name('major.add')->middleware('super-admin');
	Route::post('major/add', [MajorController::class, 'store'])->name('major.store')->middleware('super-admin');
	Route::get('major/edit/{id}', [MajorController::class, 'edit'])->name('major.edit')->middleware('super-admin');
	Route::post('major/update/{id}', [MajorController::class, 'update'])->name('major.update')->middleware('super-admin');
	Route::get('major/delete/{id}', [MajorController::class, 'delete'])->name('major.delete')->middleware('super-admin');

	// ex comd routes
	Route::get('excomanderd/list', [ExComanderController::class, 'list'])->name('excomd.list')->middleware('super-admin');
	Route::get('excomanderd/add', [ExComanderController::class, 'add'])->name('excomd.add')->middleware('super-admin');
	Route::post('excomanderd/add', [ExComanderController::class, 'store'])->name('excomd.store')->middleware('super-admin');
	Route::get('excomanderd/edit/{id}', [ExComanderController::class, 'edit'])->name('excomd.edit')->middleware('super-admin');
	Route::post('excomanderd/update/{id}', [ExComanderController::class, 'update'])->name('excomd.update')->middleware('super-admin');
	Route::get('excomanderd/delete/{id}', [ExComanderController::class, 'delete'])->name('excomd.delete')->middleware('super-admin');


	// ex sso routes
	Route::get('exsso/list', [ExSsoController::class, 'list'])->name('exsso.list')->middleware('super-admin');
	Route::get('exsso/add', [ExSsoController::class, 'add'])->name('exsso.add')->middleware('super-admin');
	Route::post('exsso/add', [ExSsoController::class, 'store'])->name('exsso.store')->middleware('super-admin');
	Route::get('exsso/edit/{id}', [ExSsoController::class, 'edit'])->name('exsso.edit')->middleware('super-admin');
	Route::post('exsso/update/{id}', [ExSsoController::class, 'update'])->name('exsso.update')->middleware('super-admin');
	Route::get('exsso/delete/{id}', [ExSsoController::class, 'delete'])->name('exsso.delete')->middleware('super-admin');

	// Photo Gallery Routes
	Route::get('photo_gallery/list', [PhotoGalleryController::class, 'list'])->name('photo_gallery.list')->middleware('super-admin');
	Route::get('photo_gallery/add', [PhotoGalleryController::class, 'add'])->name('photo_gallery.add')->middleware('super-admin');
	Route::post('photo_gallery/add', [PhotoGalleryController::class, 'store'])->name('photo_gallery.store')->middleware('super-admin');
	Route::get('photo_gallery/edit/{id}', [PhotoGalleryController::class, 'edit'])->name('photo_gallery.edit')->middleware('super-admin');
	Route::post('photo_gallery/edit/{id}', [PhotoGalleryController::class, 'update'])->name('photo_gallery.update')->middleware('super-admin');
	Route::get('photo_gallery/delete/{id}', [PhotoGalleryController::class, 'delete'])->name('photo_gallery.delete')->middleware('super-admin');
	// PDF File Routes
	Route::get('pdf_file/list', [PdfFileController::class, 'list'])->name('pdf_file.list')->middleware('super-admin');
	Route::get('pdf_file/add', [PdfFileController::class, 'add'])->name('pdf_file.add')->middleware('super-admin');
	Route::post('pdf_file/add', [PdfFileController::class, 'store'])->name('pdf_file.store')->middleware('super-admin');
	Route::get('pdf_file/edit/{id}', [PdfFileController::class, 'edit'])->name('pdf_file.edit')->middleware('super-admin');
	Route::post('pdf_file/update/{id}', [PdfFileController::class, 'update'])->name('pdf_file.update')->middleware('super-admin');
	Route::get('pdf_file/delete/{id}', [PdfFileController::class, 'delete'])->name('pdf_file.delete')->middleware('super-admin');
	// Fixed File Routes
	Route::get('fixed_file/add', [FixedFileController::class, 'add'])->name('fixed_file.add')->middleware('super-admin');
	Route::post('fixed_file/add', [FixedFileController::class, 'store'])->name('fixed_file.store')->middleware('super-admin');
	// Slogan Routes
	Route::get('slogan/add', [SloganController::class, 'add'])->name('slogan.add')->middleware('super-admin');
	Route::post('slogan/add', [SloganController::class, 'store'])->name('slogan.store')->middleware('super-admin');

	// payment report list route
	Route::get('payment_report/list', [PaymentController::class, 'paymentReportData'])->name('payment-report.list');
	Route::get('approved_payment_report/datatable', [PaymentController::class, 'paymentReportDatatable'])->name('approved_payment_report.datatable');
	Route::delete('/payments/delete/{payment}', [PaymentController::class, 'delete'])->name('payments.delete');

	Route::get('new-payment_report/list', [PaymentController::class, 'newpaymentReportData'])->name('new-payment-report.list');
	Route::get('new-approved_payment_report/datatable', [PaymentController::class, 'newpaymentReportDatatable'])->name('new_approved_payment_report.datatable');

	//def payment report list route
	Route::get('def_payment_report/list', [PaymentController::class, 'defpaymentReportData'])->name('def-payment-report.list')->middleware('super-admin');
	Route::get('def_approved_payment_report/datatable', [PaymentController::class, 'defpaymentReportDatatable'])->name('def_payment_report.datatable');

	//def payment report list route
	Route::get('non_def_payment_report/list', [PaymentController::class, 'NondefPaymentReportData'])->name('nondef-payment-report.list')->middleware('super-admin');
	Route::get('non_def_approved_payment_report/datatable', [PaymentController::class, 'NondefPaymentReportDatatable'])->name('nondef_payment_report.datatable');


	//paymentgateways report
	Route::get('/all-paid-recollect/application', [PaymentController::class, 'allPaidRecollect']);
	// Route::post('/search/sticker/report',[HomeController::class, 'searchStickerReport']);
});

Auth::routes();
Route::group(['prefix' => 'customer'], function () {

	Route::get('login', [LoginController::class, 'showLoginForm'])->name('customer.login');
	// Route::get('login', [LoginController::class, 'showLoginForm'])->middleware('not-auth')->name('customer.login');
	Route::post('login/verify', [LoginController::class, 'verifyLogin'])->name('customer.login.verify');
	Route::post('login', [LoginController::class, 'login'])->middleware('not-auth')->name('customer.login');
	// Registration Routes...
	Route::get('register', [RegisterController::class, 'showRegistrationForm'])->middleware('not-auth')->name('customer.register');
	Route::post('register', [RegisterController::class, 'register'])->middleware('not-auth');

	Route::post('logout', [LoginController::class, 'logout'])->name('customer.logout');

	// Password Reset SMS Routes...
	Route::get('password/reset/sms', [ForgotPasswordSMSController::class, 'index'])->name('customer.password.sms.index')->middleware('not-auth');
	Route::post('password/reset/sms/send/verification_code', [ForgotPasswordSMSController::class, 'sendVerificationCode'])->name('customer.password.sms.send.verification_code')->middleware('not-auth');
	Route::get('password/reset/sms/verify', [ForgotPasswordSMSController::class, 'verify'])->name('customer.password.sms.verify')->middleware('not-auth');
	Route::post('password/reset/sms/verify', [ForgotPasswordSMSController::class, 'verifyPost'])->name('customer.password.sms.verify.post')->middleware('not-auth');
	Route::get('password/reset/sms/reset', [ForgotPasswordSMSController::class, 'reset'])->name('customer.password.sms.reset')->middleware('not-auth');
	Route::post('password/reset/sms/reset', [ForgotPasswordSMSController::class, 'resetPost'])->name('customer.password.sms.reset.post')->middleware('not-auth');
	Route::get('password/reset/sms/success', [ForgotPasswordSMSController::class, 'success'])->name('customer.password.sms.success')->middleware('not-auth');



	// Password Reset Routes...
	//Route::get('password/reset', 'CustomerAuth\ForgotPasswordController@showLinkRequestForm')->name('customer.password.request')->middleware('not-auth');
	//Route::post('password/email', 'CustomerAuth\ForgotPasswordController@sendResetLinkEmail')->name('customer.password.email')->middleware('not-auth');
	//Route::get('password/reset/{token}', 'CustomerAuth\ResetPasswordController@showResetForm')->name('customer.password.reset')->middleware('not-auth');
	//Route::post('password/reset', 'CustomerAuth\ResetPasswordController@reset')->middleware('not-auth');


});
Route::get('/clear-cache', function () {
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:cache');
	return 'DONE'; //Return anything
});

Route::post('twoait/getsmscheck', [HomeController::class, 'twoait_getsmscheck']);


Route::get('test', [HomeController::class, 'rhythm']);
Route::get('renew/sms', [VehicleStickerController::class, 'renewSMS']);
Route::post('renew/sms', [VehicleStickerController::class, 'sendRenewSMS']);

Route::get('previous-remark-update', [FrontController::class, 'previousRemarkUpdate']);


//payment gateway
Route::get('payment/view/{id}', [PaymentController::class, 'paymentView'])->name('payment.view');
Route::get('payment_success/{id}', [PaymentController::class, 'payment_success'])->name('payment_success');
Route::get('payment/after/success/{id}', [PaymentController::class, 'payment_after_sucess'])->name('payment_after_success');

// Route::post('applicationForm/store','ApplicationController@applicationFormStore')->middleware('applicant');

Route::post('make_payment', [SSlComarzController::class, 'make_payment'])->name('make_payment');
Route::match(['get', 'post'], 'subscriber/sslcommerz/success', [SSlComarzController::class, 'success'])->name('success');
Route::match(['get', 'post'], 'subscriber/sslcommerz/fail', [SSlComarzController::class, 'fail'])->name('failure');
Route::match(['get', 'post'], 'subscriber/sslcommerz/cancel', [SSlComarzController::class, 'cancel'])->name('cancel');
Route::post('subscriber/sslcommerz/ipn', [SSlComarzController::class, 'ipn'])->name('ipn');


//footer importent link route start here
Route::get('footer/about_us', [ImportentLinkController::class, 'about_us'])->name('footer.about_us');
Route::get('footer/privacy_policy', [ImportentLinkController::class, 'privacy_policy'])->name('footer.privacy_policy');
Route::get('footer/terms_condition', [ImportentLinkController::class, 'terms_condition'])->name('footer.terms_condition');
Route::get('footer/return_refund', [ImportentLinkController::class, 'return_refund'])->name('footer.return_refund');


Route::post('admin/payment', [SSlComarzController::class, 'paymentByAdmin'])->name('paymentbyadmin');

Route::resource('add-e-type/registration-number', AddETypeRegistrationNumber::class);
Route::resource('admin/vehicle-type', VehicleTypeController::class);
Route::get('admin/application/verify/{def}', [ETypeApplicationController::class, 'verifyApplication'])->name('verify_application');
Route::get('admin/application/verify/datatable/mostafijur/', [ETypeApplicationController::class, 'allVerifyDatatable11'])->name('verify_application.datatable');
Route::get('admin/application/e-type-1/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.1');
Route::get('admin/application/e-type-2/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.2');
Route::get('admin/application/e-type-3/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.3');
Route::get('admin/application/e-type-4/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.4');
Route::get('admin/application/e-type-5/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.5');
Route::get('admin/application/e-type-6/', [ETypeApplicationController::class, 'eTypeApplication'])->name('e_type_application.6');
Route::get('admin/application/e-type/datatable/mostafijur', [ETypeApplicationController::class, 'allETypeDatatable'])->name('e_type_application.datatable');
Route::post('admin/verify/application', [ETypeApplicationController::class, 'applicationVerify'])->name('admin.verify.application');

Route::post('admin/delivery/application', [ETypeApplicationController::class, 'applicationdelivery'])->name('admin.delivery.application');

// delivery sticker information store & send sms logic write
Route::get('/all-delivery/application', [HomeController::class, 'allIdelivery'])->middleware('auth');
Route::get('/delivery_apps/datatable', [HomeController::class, 'deliveryAppsDatatable'])->name('delivery_apps.datatable');

// print  sticker information store 
Route::get('/all-print/application', [HomeController::class, 'allPrint'])->middleware('auth');
Route::get('/print_apps/datatable', [HomeController::class, 'printAppsDatatable'])->name('print_apps.datatable');



// new routes
Route::get('/gallery', [FrontController::class, 'gallery'])->name('gallery');
Route::get('/staf-org', [FrontController::class, 'staf'])->name('staf-org');
Route::get('/staf-history', [FrontController::class, 'history'])->name('staf-history');

// contact number routes
Route::get('contact/list', [ContactController::class, 'list'])->name('contact.list')->middleware('super-admin');
Route::get('contact/add', [ContactController::class, 'add'])->name('contact.add')->middleware('super-admin');
Route::post('contact/add', [ContactController::class, 'store'])->name('contact.store')->middleware('super-admin');
Route::get('contact/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit')->middleware('super-admin');
Route::post('contact/update/{id}', [ContactController::class, 'update'])->name('contact.update')->middleware('super-admin');
Route::get('contact/delete/{id}', [ContactController::class, 'delete'])->name('contact.delete')->middleware('super-admin');


// Home page controller
Route::get('home-page/list', [HomePageComtroller::class, 'index'])->name('home-page.index')->middleware('super-admin');
Route::get('home-page/add', [HomePageComtroller::class, 'create'])->name('home-page.add')->middleware('super-admin');
Route::post('home-page/add', [HomePageComtroller::class, 'store'])->name('home-page.store')->middleware('super-admin');
Route::get('home-page/edit/{id}', [HomePageComtroller::class, 'edit'])->name('home-page.edit')->middleware('super-admin');
Route::post('home-page/update/{id}', [HomePageComtroller::class, 'update'])->name('home-page.update')->middleware('super-admin');

Route::get('admin/application/all-approved_1/def', [AllAprovedApplication::class, 'allApplicationApproveDef'])->name('admin.all-application-approved-def');
Route::get('admin/application/all-approved_1/non-def', [AllAprovedApplication::class, 'allApplicationApproveNonDef'])->name('admin.all-application-approved-non-def');
Route::get('admin/application/all-delivery_1', [AllAprovedApplication::class, 'allApplicationDelivery'])->name('admin.applicationDelivery');

Route::get('admin/application/lastet-serial', [AllAprovedApplication::class, 'getStickerLastestSerialNumberByCategory'])->name('admin.latest-serial');

Route::post('admin/resent-schedule-sms', [ApplicationController::class, 'resentScheduleSms'])->name('resent_schedule_sms');


Route::get('admin/refund/{application_id}', [RefundController::class, 'create'])->name('admin.refund.create');
Route::post('admin/refund', [RefundController::class, 'store'])->name('admin.refund.store');
Route::post('admin/login/verify', [AdminLoginController::class, 'verifyLogin'])->name('admin.login.verify');

Route::get('/checking', [HomeController::class, 'checking'])->name('checking');
Route::get('/checking-result', [HomeController::class, 'checkingResult'])->name('checking-result');
Route::get('/checking-details/{app_number}', [HomeController::class, 'DetailesReview'])->name('checking_details');

Route::post('admin/ps_approved', [HomeController::class, 'ps_approved'])->name('admin.ps_approved');
Route::post('admin/ps_approved_remove', [HomeController::class, 'ps_approved_remove'])->name('admin.ps_approved_remove');
