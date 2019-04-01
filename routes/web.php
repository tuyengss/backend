<?php
use Nesk\Puphpeteer\Puppeteer;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('https://creditnow.vn/faq');
});

Route::get('/crawler', function () {
    $puppeteer = new Puppeteer;
    $browser = $puppeteer->launch();

    $page = $browser->newPage();
    $page->goto('https://example.com');
    $page->screenshot(['path' => 'example.png']);

    $browser->close();
});

Route::get('data','\App\Http\Controllers\Voyager\CicController@get_DB');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('logout' , function()
    {
        return redirect('https://creditnow.vn/backend/admin/login');
    });
    Route::get('/','\App\Http\Controllers\Voyager\ReportController@for_control_report')->name('voyager.dashboard');
    Route::get('log-cic/{any}', '\App\Http\Controllers\Voyager\CicController@index')->middleware('admin.user');
    //Route::get('cic-logs/{any}', ['uses' => '\App\Http\Controllers\Voyager\CicController@index', 'as' => 'getCic']); 
    Route::post('update-note-admin/{any}/{cm}','\App\Http\Controllers\Voyager\HistoryColntroller@update_note_admin');

    Route::get('history','\App\Http\Controllers\Voyager\HistoryColntroller@index');
    Route::post('history','\App\Http\Controllers\Voyager\HistoryColntroller@search_data_history');
    Route::get('user-in-group/{id}','\App\Http\Controllers\Voyager\HistoryColntroller@getUser_teAm');

    Route::get('history/{from}/{to}','\App\Http\Controllers\Voyager\HistoryColntroller@search_data_history_month');
    Route::post('send-to-cs-team','\App\Http\Controllers\Voyager\HistoryColntroller@send_to_cs_team')->name('send-to-cs-team');
    Route::get('history/view-edit/{any}/{cmnd}','\App\Http\Controllers\Voyager\HistoryColntroller@view_history_log');
    Route::get('for-control-report','\App\Http\Controllers\Voyager\ReportController@for_control_report');
    Route::get('for-control','\App\Http\Controllers\Voyager\ReportController@for_control');
    Route::post('for-control','\App\Http\Controllers\Voyager\ReportController@for_control');
    Route::post('/','\App\Http\Controllers\Voyager\ReportController@for_control_report')->name('voyager.dashboard');
    Route::get('roles/edit/{any}', '\App\Http\Controllers\Voyager\PermisionController@index');
    
    Route::post('send-credit-inst','\App\Http\Controllers\Voyager\HistoryColntroller@send_credit_inst');

    Route::get('partner', '\App\Http\Controllers\Voyager\UserController@get_user_tctd');
    Route::post('partner', '\App\Http\Controllers\Voyager\UserController@get_user_tctd');

    Route::get('borrower', '\App\Http\Controllers\Voyager\UserController@get_user_nguoivay');
    Route::post('borrower', '\App\Http\Controllers\Voyager\UserController@get_user_nguoivay');

    Route::get('agencines', '\App\Http\Controllers\Voyager\UserController@get_user_agencines');
    Route::post('agencines', '\App\Http\Controllers\Voyager\UserController@get_user_agencines');

    Route::get('customer-care', '\App\Http\Controllers\Voyager\UserController@get_user_customer_care');
    Route::post('customer-care', '\App\Http\Controllers\Voyager\UserController@get_user_customer_care');

    Route::get('customer-care-manager', '\App\Http\Controllers\Voyager\UserController@get_user_customercaremanager');
    Route::post('customer-care-manager', '\App\Http\Controllers\Voyager\UserController@get_user_customercaremanager');


    Route::post('updatemoney/{id}', '\App\Http\Controllers\Voyager\HistoryColntroller@savemoney');


    /******************************report -> kế toán**********************************************/
    Route::get('report-customer-care','\App\Http\Controllers\Voyager\ReportKTController@report_customer_care');
    Route::post('report-customer-care','\App\Http\Controllers\Voyager\ReportKTController@report_customer_care');
    Route::post('report-customer-care-save','\App\Http\Controllers\Voyager\ReportKTController@save_note_kt');

    Route::get('report-agencies','\App\Http\Controllers\Voyager\ReportKTController@report_agencies');
    Route::post('report-agencies','\App\Http\Controllers\Voyager\ReportKTController@report_agencies');
    Route::post('report-agencies-save','\App\Http\Controllers\Voyager\ReportKTController@save_note_kt_agenci');
    
    Route::get('report-credit-institutions','\App\Http\Controllers\Voyager\ReportKTController@report_tctd');
    Route::post('report-credit-institutions','\App\Http\Controllers\Voyager\ReportKTController@report_tctd');
    Route::post('report-credit-institutions-save','\App\Http\Controllers\Voyager\ReportKTController@save_note_kt_tctd');

    Route::post('update-profile/{id}','\App\Http\Controllers\Voyager\UserController@update_profile');
    /*********************************************************************************************/
    /***********************************CS-MCS***********************************************/
    Route::get('manager-partner-cs','\App\Http\Controllers\Voyager\CustomercareController@manager_parrtner_tctd');
    Route::get('manager-partner-cs/{id}','\App\Http\Controllers\Voyager\CustomercareController@view_partner_cs');
    Route::post('manager-partner-cs/{id}','\App\Http\Controllers\Voyager\CustomercareController@view_partner_cs');

    Route::post('save-cs-job/{id}','\App\Http\Controllers\Voyager\CustomercareController@save_cs_job');

    Route::post('update-tctd','\App\Http\Controllers\Voyager\CustomercareController@update_tctd')->name('update-tctd');

    Route::post('add-new-tctd','\App\Http\Controllers\Voyager\CustomercareController@add_tctd')->name('add-new-tctd');
    Route::get('agency','\App\Http\Controllers\Voyager\CustomercareController@manager_agency');
    Route::post('update-agency','\App\Http\Controllers\Voyager\CustomercareController@update_agency')->name('update-agency');
    
    Route::post('add-new-agency','\App\Http\Controllers\Voyager\CustomercareController@add_new_agency')->name('add-new-agency');

    Route::get('report-mcs-cs-a','\App\Http\Controllers\Voyager\CustomercareController@report_mcs');
    Route::post('report-mcs-cs-a','\App\Http\Controllers\Voyager\CustomercareController@report_mcs');
    Route::get('report-partner','\App\Http\Controllers\Voyager\CustomercareController@report_partner');
    Route::get('report-agency','\App\Http\Controllers\Voyager\CustomercareController@report_agency');
    Route::get('user-cs','\App\Http\Controllers\Voyager\CustomercareController@manager_user_cs');
    
    /******************************referal*******************************************/
    /******************************referal*******************************************/
    Route::get('referals','\App\Http\Controllers\Voyager\ReferalsController@index')->name('voyager.referals.index');
    Route::post('referals','\App\Http\Controllers\Voyager\ReferalsController@index')->name('voyager.referals.index');
    Route::post('add-referals','\App\Http\Controllers\Voyager\ReferalsController@add_referal');
    Route::get('delete-referals/{id}','\App\Http\Controllers\Voyager\ReferalsController@trash_referal');



});
