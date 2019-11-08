import axios from 'axios'
/**
 * getAllFreeUsers => get free users which are neither seniors or juniors at current time
 *
 * getTeam => get Tree structure of our juniors and seniors
 *
 * changeUserStatus => update location of user as junior/senior/free
 *   obj params:
 *    mode: 1 => user will get free
 *    mode: 2 => user will be promoted to senior
 *    mode: 3 => user will be promoted/demoted junior
 *    senior_id: if user is junior, give his senior id
 *    id: give user's id
 *
 * addInstock => add new stock
 *
 *
 * deleteImage => delete the image
 *  obj params:
 *      id: id of image
 * getImages => get all images
 *  obj params:
 *      account_id: account id of user
 *
 * updateDocumentDetails => update the image
 *  obj params:
 *      id: id of image
 *      image(File): if image changed, then give image (optional)
 *      description: description of image
 *
 * listOptions => list of order status
 *
 * shippingList => list of users and point counts
 *  obj params:
 *      startDate
 *      fromDat
 *      page(starts from 0)
 *
 * shippingByUser => get points and shipping detail of indivitual user
 *  obj params:
 *      startDate
 *      fromDate
 *      page(starts from 0)
 *      id
 */


export default {
    getAllFreeUsers: () => axios.get('/admin/user/getFreeUsers'),
    getTeam: () => axios.get('/admin/user/getTeam'),
    changeUserStatus: (obj) => axios.post('/admin/user/ChangeTeamUserStatus', obj),
    addInstock: (obj) => axios.post('/api/AddInstock', obj),
    deleteImage: (obj) => axios.post('/api/DeleteDocumentDetails', obj),
    getImages: (obj) => axios.post('/api/GetDocumentDetails', obj),
    // updateDocumentDetails: (obj, header) => axios.post('/api/UpdateDocumentDetails', obj, header),
    updateDocumentDetails: (obj, header) => axios.post('/api/UpdateDocumentDetails', obj, {
        headers: {
            'Content-Type': 'multipart/form-data'
          }
    }),
    listOptions: () => axios.get('/api/listOptions'),
    shippingList: (obj) => axios.post('/api/shippingList', obj),
    shippingByUser: (obj) => axios.post('/api/shippingByUser', obj),
    updateInventory: (obj) => axios.post('/api/updateInventory', obj),
    updateCore: (obj) => axios.post('/api/updateCore', obj),
    checkpcmhw: (obj) => axios.post('/api/checkpcmhw', obj),
    ChangeOnBoardTesting: (obj) => axios.post('/api/ChangeOnBoardTesting', obj),
    savepcmhw: (obj) => axios.post('/api/savepcmhw', obj),
    RMAForm: (obj) => axios.post('/api/RMAForm', obj),
    RMAFormDownload: (obj) => axios.post('/api/RMAFormDownload', obj),
    RMAFormSendFromEmail: (obj) => axios.post('/api/RMAFormSendFromEmail', obj),
    SendRmaFormEmail: (obj) => axios.post('/api/SendRmaFormEmail', obj),
    scannerHistory: (obj) => axios.post('/api/scannerHistory', obj),
    programmingList: (obj) => axios.get('/api/programming-list', obj),
    programUserList: (obj) => axios.get('/api/program-user-list', obj),
    ShippingSearchResult: (obj) => axios.post('/api/ShippingSearchResult', obj),
    accountManagerList: (obj) => axios.get('/api/account-manager-list', obj),
    accountManagerListDownload: (obj) => axios.get('/api/account-manager-list-download', obj),
    accountManagerPoints: (obj) => axios.get('/api/account-manager-points', obj),
    accountManagerPointsDownload: (obj) => axios.get('/api/account-manager-points-download', obj),
    CreateEasyPost: (obj) => axios.post('/api/CreateEasyPost', obj),
    CreateJunkyardToUs: (obj) => axios.post('/api/CreateJunkyardToUs', obj),
    GetPatchNotes: () => axios.get('/api/GetPatchNotes'),
    CreatePatchNotes: (obj) => axios.post('/api/CreatePatchNotes', obj),
    BuyEasyPost: (obj) => axios.post('/api/BuyEasyPost', obj),
    BuyEasyPostJunkyard: (obj) => axios.post('/api/BuyEasyPostJunkyard', obj),
    programmerList: (obj) => axios.post('/api/programmerList', obj),
    programmerByUser: (obj) => axios.post('/api/programmerByUser', obj),
    fetchState: () => axios.get('/api/fetch-state'),
    updateOrCreateVendor: (id, obj) => axios.post(`/api/update-or-create-vendor/${id}`, obj),
    updateOrCreateTracking: (id, obj) => axios.post(`/api/update-or-create-tracking/${id}`, obj),
    updateOrCreateEntry: (id, obj) => axios.post(`/api/update-or-create-entry/${id}`, obj),
    updateCustomer: (id, obj) => axios.post(`/api/update-customer/${id}`, obj),
    GetPingemployees: () => axios.get('/api/GetPingemployees'),
    PostPingemployees: (obj) => axios.post('/api/PostPingemployees', obj),
    GetMyPing: () => axios.get('/api/GetMyPing'),
    GetRmaFormsData: (obj) => axios.post('/api/GetRmaFormsData', obj),
    GetRmaFormsList: (obj) => axios.post('/api/GetRmaFormsList', obj),
    CreateRmaForms: (obj) => axios.post('/api/CreateRmaForms', obj),
    GetDocusignData: (obj) => axios.post('/api/GetDocusignData', obj),
    SendDocusignFormEmail: (obj) => axios.post('/api/SendDocusignFormEmail', obj),
    GetDocusignDataByToken: (obj) => axios.post('/api/GetDocusignDataByToken', obj),
    CreateDocusignForm: (obj) => axios.post('/api/CreateDocusignForm', obj),
    PaypalPoints: (obj) => axios.post('/api/PaypalPoints', obj),
    CaseClosedPoints: (obj) => axios.post('/api/CaseClosedPoints', obj),
    NegativeFeedbackRemovedPoints: (obj) => axios.post('/api/NegativeFeedbackRemovedPoints', obj),
    BBBPoints: (obj) => axios.post('/api/BBBPoints', obj),
    LateShipmentRemoval: (obj) => axios.post('/api/LateShipmentRemoval', obj),
    SecondaryManagerPoint: (obj) => axios.post('/api/SecondaryManagerPoint', obj),
    GetRMADataByToken: (obj) => axios.post('/api/GetRMADataByToken', obj),
    SecondaryManagerPointHistory: (obj) => axios.post('/api/SecondaryManagerPointHistory', obj),
    PointsBreakdown: (obj) => axios.post('/api/PointsBreakdown', obj), 
    PointsBreakdownDelete: (obj) => axios.post('/api/PointsBreakdownDelete', obj),
    StockMaster: () => axios.get('/api/StockMaster'),
    GetApiOrderInsert: () => axios.get('/api/GetApiOrderInsert'),
    UpdateApiOrderInsert: (obj) => axios.post('/api/UpdateApiOrderInsert', obj),
    fetchOrderInfo: () => axios.get('/api/fetch-order-info'),
    checkMagentoOrders: (obj) => axios.post('/api/check-magento-orders', obj),
    checkEbayOrders: (obj) => axios.post('/api/check-ebay-orders', obj),
    addMagentoOrdersToInventory: (obj) => axios.post('/api/add-magento-orders', obj),
    addEbayOrdersToInventory: (obj) => axios.post('/api/add-ebay-orders', obj), 
    RmaPdfDownload: (obj) => axios.post('/api/RmaPdfDownload', obj), 
    fetchEbayOrderInfo: () => axios.get('/api/fetch-ebay-order-info'), 
    addEbayOrderLive: (obj) => axios.post('/api/add-ebay-order-live', obj), 
    getMetadata: (obj) => axios.post('/api/getMetadata', obj), 
    AdminEmails: (obj) => axios.post('/api/AdminEmails', obj), 
    getShopNameVendor: () => axios.get('/api/getShopNameVendor'), 
    shippingListCount: (obj) => axios.post('/api/shippingListCount', obj),
    customerVINMatches: (obj) => axios.post('/api/customerVINMatches', obj), 
    deleteOrderStatus: (obj) => axios.post('/api/deleteOrderStatus', obj),
}
