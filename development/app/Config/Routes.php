<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Users');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Users::index',['filter'=>'noauth']);
$routes->get('/users', 'Users::index',['filter'=>'noauth']);
$routes->match(['get','post'],'logout', 'Users::logout');   
$routes->match(['get','post'],'register', 'Users::register',['filter'=>'noauth']);
$routes->match(['get','post'],'profile', 'Users::profile',['filter'=>'authprofile']);
$routes->get('dashboard', 'Dashboard::index',['filter'=>'auth']);

$routes->match(['get','post'],'country', 'Country::index',['filter'=>'auth']);
$routes->match(['get','post'],'state', 'Country::state',['filter'=>'auth']);
$routes->match(['get','post'],'city', 'Country::city',['filter'=>'auth']);
$routes->match(['get','post'],'Dependent', 'Dependent::index');
$routes->match(['get','post'],'countries', 'Dependent::countries');
$routes->match(['get','post'],'states', 'Dependent::states');
$routes->match(['get','post'],'cities', 'Dependent::cities');

$routes->match(['get','post'],'checklistcontrol', 'checklist::index',['filter'=>'auth']);
$routes->match(['get','post'],'checklist', 'checklist::index',['filter'=>'auth']);
$routes->match(['get','post'],'mainChecklist', 'checklist::mainChecklist',['filter'=>'auth']);
$routes->match(['get','post'],'updateMainchecklist', 'checklist::updateMainchecklist',['filter'=>'auth']);
$routes->match(['get','post'],'mainChecklistEdit/(:any)', 'checklist::mainChecklistEdit/$1',['filter'=>'auth']);
$routes->get('mainChecklistDelete/(:any)', 'checklist::mainChecklistDelete/$1',['filter'=>'auth']);

$routes->match(['get','post'],'mainAraAdding/(:any)', 'checklist::mainAraAdding/$1',['filter'=>'auth']);
$routes->match(['get','post'],'mainAraEdit/(:any)', 'checklist::mainAraEdit/$1',['filter'=>'auth']);
$routes->match(['get','post'],'mainAreaDeleted/(:any)', 'checklist::mainAreaDeleted/$1',['filter'=>'auth']);

$routes->match(['get','post'],'firstSubArea/(:any)', 'checklist::firstSubArea/$1',['filter'=>'auth']);
$routes->match(['get','post'],'firstSubAreaEdit/(:any)', 'checklist::firstSubAreaEdit/$1',['filter'=>'auth']);
$routes->match(['get','post'],'firstSubAreaDeleted/(:any)', 'checklist::firstSubAreaDeleted/$1',['filter'=>'auth']);

$routes->match(['get','post'],'checkingItem/(:any)', 'checklist::checkingItem/$1',['filter'=>'auth']);
$routes->match(['get','post'],'checkingItemEdit/(:any)', 'checklist::checkingItemEdit/$1',['filter'=>'auth']);
$routes->match(['get','post'],'checkingItemDeleted/(:any)', 'checklist::checkingItemDeleted/$1',['filter'=>'auth']);

$routes->match(['get','post'],'AuditCreation', 'AuditCreation::index',['filter'=>'auth']);
$routes->match(['get','post'],'checklist_id', 'AuditCreation::checklist_id',['filter'=>'auth']);
$routes->match(['get','post'],'entityid', 'AuditCreation::entityid',['filter'=>'auth']);

$routes->match(['get','post'],'auditcreated', 'AuditCreation::auditcreated',['filter'=>'auth']);
$routes->match(['get','post'],'auditcreationEdit/(:any)', 'AuditCreation::auditcreationEdit/$1',['filter'=>'auth']);
$routes->match(['get','post'],'auditcreationDelete/(:any)', 'AuditCreation::auditcreationDelete/$1',['filter'=>'auth']);
$routes->match(['get','post'],'allcreatedaudit/(:any)', 'AuditCreation::allcreatedaudit/$1',['filter'=>'auth']);
$routes->match(['get','post'],'createdauditsummaryyearly', 'AuditCreation::createdauditsummaryyearly',['filter'=>'auth']);
$routes->match(['get','post'],'createdauditdetailsyearly', 'AuditCreation::createdauditdetailsyearly',['filter'=>'auth']);
$routes->match(['get','post'],'createdauditdetailall', 'AuditCreation::createdauditdetailall',['filter'=>'auth']);

$routes->match(['get','post'],'allcreatedaudited', 'AuditCreation::allcreatedaudited',['filter'=>'auth']);
$routes->match(['get','post'],'createdauditdetailallnew', 'AuditCreation::createdauditdetailallnew',['filter'=>'auth']);
$routes->match(['get','post'],'createdauditdetailallyearly', 'AuditCreation::createdauditdetailallyearly',['filter'=>'auth']);

$routes->match(['get','post'],'auditteamcreation/(:any)', 'Auditteam::index/$1',['filter'=>'auth']);
$routes->match(['get','post'],'auditteamremove/(:any)', 'Auditteam::auditteamremove/$1',['filter'=>'auth']);
$routes->match(['get','post'],'allteamassignment', 'Auditteam::allteamassignment',['filter'=>'auth']);
$routes->match(['get','post'],'teamassignmentdetails', 'Auditteam::teamassignmentdetails',['filter'=>'auth']);
$routes->match(['get','post'],'auditteamdetails/(:any)', 'Auditteam::auditteamdetails/$1',['filter'=>'auth']);

$routes->match(['get','post'],'AuditFieldWork/(:any)', 'AuditFieldWork::index/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'Assignedauditall/(:any)', 'AuditFieldWork::Assignedauditall/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'Assignedauditallworks', 'AuditFieldWork::createdauditdetailall',['filter'=>'authsecond']);
$routes->match(['get','post'],'sessionauditid/(:any)', 'AuditFieldWork::sessionauditid/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'commententering/(:any)', 'AuditFieldWork::commententering/$1',['filter'=>'authcomment']);
$routes->match(['get','post'],'commentfirstareadetails', 'AuditFieldWork::commentfirstareadetails',['filter'=>'authsecond']);
$routes->match(['get','post'],'commentcheckitemdeails', 'AuditFieldWork::commentcheckitemdeails',['filter'=>'authsecond']);
$routes->match(['get','post'],'alreadycommententer', 'AuditFieldWork::alreadycommententer',['filter'=>'authsecond']);

$routes->match(['get','post'],'Ongoingallaudit/(:any)', 'AuditFieldWork::Ongoingallaudit/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ongoingassingedaudit', 'AuditFieldWork::ongoingassingedaudit',['filter'=>'authsecond']);
$routes->match(['get','post'],'sessionauditidcomment/(:any)', 'AuditFieldWork::sessionauditidcomment/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'Ongoingallcomments/(:any)', 'AuditFieldWork::Ongoingallcomments/$1',['filter'=>'authcomment']);
$routes->match(['get','post'],'OngoingCommentEdit/(:any)', 'AuditFieldWork::OngoingCommentEdit/$1',['filter'=>'authcomment']);
$routes->match(['get','post'],'OngoingCommentSubmitDelete/(:any)', 'AuditFieldWork::OngoingCommentSubmitDelete/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingCommentSubmitReview/(:any)', 'AuditFieldWork::OngoingCommentSubmitReview/$1',['filter'=>'authsecond']);

$routes->match(['get','post'],'ongoingAuditReviewing/(:any)', 'AuditReview::index/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ongoingAssignedReviewAllAudit', 'AuditReview::ongoingAssignedReviewAllAudit',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingReviewAllComments/(:any)', 'AuditReview::OngoingReviewAllComments/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingCommentReviewing/(:any)', 'AuditReview::OngoingCommentReviewing/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReviewingReject/(:any)', 'AuditReview::CommentReviewingReject/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReviewingAccept/(:any)', 'AuditReview::CommentReviewingAccept/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingCommentForward/(:any)', 'AuditReview::OngoingCommentForward/$1',['filter'=>'authsecond']);

$routes->match(['get','post'],'AuditReplying/(:any)', 'AuditReplying::index/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allOngoingAuditReplyingAssign/(:any)', 'AuditReplying::AllOngoingAuditReplyingAssign/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CurrentOngoingAuditReplyAssign', 'AuditReplying::CurrentOngoingAuditReplyAssign',['filter'=>'auththird']);
$routes->match(['get','post'],'sessionauditidReplyingAssign/(:any)', 'AuditReplying::sessionauditidReplyingAssign/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allCommentReplyingAssign/(:any)', 'AuditReplying::allCommentReplyingAssign/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CommentReplyingAssigned/(:any)', 'AuditReplying::CommentReplyingAssigned/$1',['filter'=>'auththird']);

$routes->match(['get','post'],'allOngoingAuditReplyingAdd/(:any)', 'AuditReplying::AllOngoingAuditReplyingAdd/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CurrentOngoingAuditReplyAdd', 'AuditReplying::CurrentOngoingAuditReplyAdd',['filter'=>'auththird']);
$routes->match(['get','post'],'sessionauditidReplyingAdd/(:any)', 'AuditReplying::sessionauditidReplyingAdd/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allCommentReplyingAdd/(:any)', 'AuditReplying::allCommentReplyingAdd/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CommentReplyingAdd/(:any)', 'AuditReplying::CommentReplyingAdd/$1',['filter'=>'auththird']);

$routes->match(['get','post'],'OngoingCommentReplySubmitReview/(:any)', 'AuditReplying::OngoingCommentReplySubmitReview/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allOngoingAuditReplyingReview/(:any)', 'AuditReplying::allOngoingAuditReplyingReview/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CurrentOngoingAuditReplyReview', 'AuditReplying::CurrentOngoingAuditReplyReview',['filter'=>'auththird']);
$routes->match(['get','post'],'sessionauditidReplyingReview/(:any)', 'AuditReplying::sessionauditidReplyingReview/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allCommentReplyingReview/(:any)', 'AuditReplying::allCommentReplyingReview/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CommentReplyingReview/(:any)', 'AuditReplying::CommentReplyingReview/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'OngoingCommentReplyReviewedAccepted/(:any)', 'AuditReplying::OngoingCommentReplyReviewedAccepted/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'OngoingCommentReplyReviewedSubmitAudit/(:any)', 'AuditReplying::OngoingCommentReplyReviewedSubmitAudit/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'RepliedAuditSubmitAudit/(:any)', 'AuditReplying::RepliedAuditSubmitAudit/$1',['filter'=>'auththird']);

$routes->match(['get','post'],'AuditReplyingOfficerDashboard/(:any)', 'AuditReplyingOfficer::index/$1',['filter'=>'authfourth']);
$routes->match(['get','post'],'allOngoingAuditOfficerReplyingAdd/(:any)', 'AuditReplyingOfficer::allOngoingAuditOfficerReplyingAdd/$1',['filter'=>'authfourth']);
$routes->match(['get','post'],'CurrentOngoingAuditOfficerReplyAdd', 'AuditReplyingOfficer::CurrentOngoingAuditOfficerReplyAdd',['filter'=>'authfourth']);
$routes->match(['get','post'],'sessionauditidOfficerReplyingAdd/(:any)', 'AuditReplyingOfficer::sessionauditidOfficerReplyingAdd/$1',['filter'=>'authfourth']);
$routes->match(['get','post'],'allCommentOfficerReplyingAdd/(:any)', 'AuditReplyingOfficer::allCommentOfficerReplyingAdd/$1',['filter'=>'authfourth']);
$routes->match(['get','post'],'CommentOfficerReplyingAdd/(:any)', 'AuditReplyingOfficer::CommentOfficerReplyingAdd/$1',['filter'=>'authfourth']);
$routes->match(['get','post'],'CommentOfficerReplySubmitReview/(:any)', 'AuditReplyingOfficer::CommentOfficerReplySubmitReview/$1',['filter'=>'authfourth']);

$routes->match(['get','post'],'AuditReplyReview/(:any)', 'AuditReplyReview::index/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ongoingAssignedReplyReviewAllAudit', 'AuditReplyReview::ongoingAssignedReplyReviewAllAudit',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingReplyReviewAllComments/(:any)', 'AuditReplyReview::OngoingReplyReviewAllComments/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingCommentReplyReviewing/(:any)', 'AuditReplyReview::OngoingCommentReplyReviewing/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReplyReviewingReject/(:any)', 'AuditReplyReview::CommentReplyReviewingReject/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReplyReviewingAccepted/(:any)', 'AuditReplyReview::CommentReplyReviewingAccepted/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReplyReviewManagmentAccept/(:any)', 'AuditReplyReview::CommentReplyReviewManagmentAccept/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ReplyReviewForwardBranch/(:any)', 'AuditReplyReview::ReplyReviewForwardBranch/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ReplyReviewForwardBranch/(:any)', 'AuditReplyReview::ReplyReviewForwardBranch/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'AditFinalisation/(:any)', 'AuditReplyReview::AditFinalisation/$1',['filter'=>'authsecond']);


$routes->match(['get','post'],'AuditReplyCommentEdit/(:any)', 'AuditReplyCommentEdit::index/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'ongoingAssignedReplyCommentEditAllAudit', 'AuditReplyCommentEdit::ongoingAssignedReplyCommentEditAllAudit',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingReplyEditingAllComments/(:any)', 'AuditReplyCommentEdit::OngoingReplyEditingAllComments/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'OngoingCommentReplyCommentEditing/(:any)', 'AuditReplyCommentEdit::OngoingCommentReplyCommentEditing/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CommentReplyEditSubmitRview/(:any)', 'AuditReplyCommentEdit::CommentReplyEditSubmitRview/$1',['filter'=>'authsecond']);

$routes->match(['get','post'],'AuditFinalised/(:any)', 'AuditFinalised::index/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CompletedAllAudit', 'AuditFinalised::CompletedAllAudit',['filter'=>'authsecond']);
$routes->match(['get','post'],'AllCompletedComments/(:any)', 'AuditFinalised::AllCompletedComments/$1',['filter'=>'authsecond']);
$routes->match(['get','post'],'CompletedComment/(:any)', 'AuditFinalised::CompletedComment/$1',['filter'=>'authsecond']);

$routes->match(['get','post'],'AllReplyCompleted/(:any)', 'AuditReplyCompleted::index/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'AllAuditReplyCompleted', 'AuditReplyCompleted::AllAuditReplyCompleted',['filter'=>'auththird']);
$routes->match(['get','post'],'sessionauditidReplyingCompleted/(:any)', 'AuditReplyCompleted::sessionauditidReplyingCompleted/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'allCommentReplyingCompleted/(:any)', 'AuditReplyCompleted::allCommentReplyingCompleted/$1',['filter'=>'auththird']);
$routes->match(['get','post'],'CommentReplyingCompleted/(:any)', 'AuditReplyCompleted::CommentReplyingCompleted/$1',['filter'=>'auththird']);

$routes->match(['get','post'],'entityadding', 'Admin::entityadding',['filter'=>'auth']);
$routes->match(['get','post'],'entityEditing/(:any)', 'Admin::entityEditing/$1',['filter'=>'auth']);
$routes->match(['get','post'],'entityDelete/(:any)', 'Admin::entityDelete/$1',['filter'=>'auth']);

$routes->match(['get','post'],'ReportAdminDashboard', 'ReportAdmin::index',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminAllChecklist', 'ReportAdmin::ReportAdminAllChecklist',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminAllChecklistExcelExport', 'ReportAdmin::ReportAdminAllChecklistExcelExport',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminAllChecklistMainSub/(:any)', 'ReportAdmin::ReportAdminAllChecklistMainSub/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminChecklistMainSubExcelExport/(:any)', 'ReportAdmin::ReportAdminChecklistMainSubExcelExport/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminFirstSubArea/(:any)', 'ReportAdmin::ReportAdminFirstSubArea/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminChecklistFirstSubExcelExport/(:any)', 'ReportAdmin::ReportAdminChecklistFirstSubExcelExport/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminCheckingItem/(:any)', 'ReportAdmin::ReportAdminCheckingItem/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminChecklistCheckingItemExport/(:any)', 'ReportAdmin::ReportAdminChecklistCheckingItemExport/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminEntireChecklist/(:any)', 'ReportAdmin::ReportAdminEntireChecklist/$1',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdmincreatedaudittype/(:any)', 'ReportAdmin::ReportAdmincreatedaudittype/$1',['filter'=>'AuthReportAudit']);

$routes->match(['get','post'],'ReportAdminAllCreatedAudit', 'ReportAdmin::ReportAdminAllCreatedAudit',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminAllCreatedAuditYearly', 'ReportAdmin::ReportAdminAllCreatedAuditYearly',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminCreatedAuditYearlyWise', 'ReportAdmin::ReportAdminCreatedAuditYearlyWise',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminCreatedAuditYearlyWiseExcel/(:any)/(:any)', 'ReportAdmin::ReportAdminCreatedAuditYearlyWiseExcel/$1/$2',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'ReportAdminCreatedAuditFiltering', 'ReportAdmin::ReportAdminCreatedAuditFiltering',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'AdminReportteamassitypedata', 'ReportAdmin::AdminReportteamassitypedata',['filter'=>'AuthReportAudit']);

$routes->match(['get','post'],'ReportAdminAllTeamFilstering', 'ReportAdmin::ReportAdminAllTeamFilstering',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'AdminReportteamassignmentdetails', 'ReportAdmin::AdminReportteamassignmentdetails',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'AdminReportteamassignmentdetdata', 'ReportAdmin::AdminReportteamassignmentdetdata',['filter'=>'AuthReportAudit']);
$routes->match(['get','post'],'AdminReportteamAssignmentExcel/(:any)/(:any)', 'ReportAdmin::AdminReportteamAssignmentExcel/$1/$2',['filter'=>'AuthReportAudit']);

$routes->get('products/(:any)', 'ProductController::find/$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
