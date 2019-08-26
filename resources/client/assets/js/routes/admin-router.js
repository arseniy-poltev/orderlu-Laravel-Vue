import Vue from 'vue'
import VueRouter from 'vue-router'

import ChangePassword from '../components/ChangePassword.vue'

import CourierCompaniesIndex from '../components/admin/cruds/CourierCompanies/Index.vue'
import CourierCompaniesCreate from '../components/admin/cruds/CourierCompanies/Create.vue'
import CourierCompaniesShow from '../components/admin/cruds/CourierCompanies/Show.vue'
import CourierCompaniesEdit from '../components/admin/cruds/CourierCompanies/Edit.vue'

import CourierRoutersIndex from '../components/admin/cruds/CourierRouters/Index.vue'
import CourierRoutersCreate from '../components/admin/cruds/CourierRouters/Create.vue'
import CourierRoutersShow from '../components/admin/cruds/CourierRouters/Show.vue'
import CourierRoutersEdit from '../components/admin/cruds/CourierRouters/Edit.vue'

import PrinterCompaniesIndex from '../components/admin/cruds/PrinterCompanies/Index.vue'
import PrinterCompaniesCreate from '../components/admin/cruds/PrinterCompanies/Create.vue'
import PrinterCompaniesShow from '../components/admin/cruds/PrinterCompanies/Show.vue'
import PrinterCompaniesEdit from '../components/admin/cruds/PrinterCompanies/Edit.vue'

import PrinterRoutersIndex from '../components/admin/cruds/PrinterRouters/Index.vue'
import PrinterRoutersCreate from '../components/admin/cruds/PrinterRouters/Create.vue'
import PrinterRoutersShow from '../components/admin/cruds/PrinterRouters/Show.vue'
import PrinterRoutersEdit from '../components/admin/cruds/PrinterRouters/Edit.vue'

import OrdersIndex from '../components/admin/cruds/Orders/Index.vue'
import OrdersCreate from '../components/admin/cruds/Orders/Create.vue'
import OrdersShow from '../components/admin/cruds/Orders/Show.vue'
import OrdersEdit from '../components/admin/cruds/Orders/Edit.vue'

import LotsIndex from '../components/admin/cruds/Lots/Index.vue'
import CheckingOrdersIndex from '../components/admin/cruds/PrinterOrders/Checking.vue'
import PrintingOrdersIndex from '../components/admin/cruds/PrinterOrders/Printing.vue'
import PrintedOrdersIndex from '../components/admin/cruds/PrinterOrders/Printed.vue'
import FinishedOrdersIndex from '../components/admin/cruds/PrinterOrders/Finished.vue'
import AllOrdersIndex from '../components/admin/cruds/PrinterOrders/All.vue'
import PrinterBooksSingle from '../components/admin/cruds/PrinterOrders/BookShow.vue'
import PrinterOrdersSingle from '../components/admin/cruds/PrinterOrders/OrderShow.vue'
import VirtualBoxesIndex from '../components/admin/cruds/VirtualBoxes/Index.vue'

import PermissionsIndex from '../components/admin/cruds/Permissions/Index.vue'
import PermissionsCreate from '../components/admin/cruds/Permissions/Create.vue'
import PermissionsShow from '../components/admin/cruds/Permissions/Show.vue'
import PermissionsEdit from '../components/admin/cruds/Permissions/Edit.vue'
import RolesIndex from '../components/admin/cruds/Roles/Index.vue'
import RolesCreate from '../components/admin/cruds/Roles/Create.vue'
import RolesShow from '../components/admin/cruds/Roles/Show.vue'
import RolesEdit from '../components/admin/cruds/Roles/Edit.vue'
import UsersIndex from '../components/admin/cruds/Users/Index.vue'
import UsersCreate from '../components/admin/cruds/Users/Create.vue'
import UsersShow from '../components/admin/cruds/Users/Show.vue'
import UsersEdit from '../components/admin/cruds/Users/Edit.vue'

import Dashboard from '../components/admin/Dashboard.vue'
Vue.use(VueRouter)

const routes = [
    { path: '/change-password', component: ChangePassword, name: 'auth.change_password' },
    /****************************Courier Company******************* */
    { path: '/courier-companies', component: CourierCompaniesIndex, name: 'courier_companies.index' },
    { path: '/courier-companies/create', component: CourierCompaniesCreate, name: 'courier_companies.create' },
    { path: '/courier-companies/:id', component: CourierCompaniesShow, name: 'courier_companies.show' },
    { path: '/courier-companies/:id/edit', component: CourierCompaniesEdit, name: 'courier_companies.edit' },
    /************************************************************* */
    /****************************Courier Router******************* */
    { path: '/courier-routers', component: CourierRoutersIndex, name: 'courier_routers.index' },
    { path: '/courier-routers/create', component: CourierRoutersCreate, name: 'courier_routers.create' },
    { path: '/courier-routers/:id', component: CourierRoutersShow, name: 'courier_routers.show' },
    { path: '/courier-routers/:id/edit', component: CourierRoutersEdit, name: 'courier_routers.edit' },
    /************************************************************* */

    /****************************Printer Company******************* */
    { path: '/printer-companies', component: PrinterCompaniesIndex, name: 'printer_companies.index' },
    { path: '/printer-companies/create', component: PrinterCompaniesCreate, name: 'printer_companies.create' },
    { path: '/printer-companies/:id', component: PrinterCompaniesShow, name: 'printer_companies.show' },
    { path: '/printer-companies/:id/edit', component: PrinterCompaniesEdit, name: 'printer_companies.edit' },
    /************************************************************* */
    /****************************Printer Router******************* */
    { path: '/printer-routers', component: PrinterRoutersIndex, name: 'printer_routers.index' },
    { path: '/printer-routers/create', component: PrinterRoutersCreate, name: 'printer_routers.create' },
    { path: '/printer-routers/:id', component: PrinterRoutersShow, name: 'printer_routers.show' },
    { path: '/printer-routers/:id/edit', component: PrinterRoutersEdit, name: 'printer_routers.edit' },
    /************************************************************* */
    //*************for Order**************/
    { path: '/orders', component: OrdersIndex, name: 'orders.index' },
    { path: '/orders/create', component: OrdersCreate, name: 'orders.create' },
    { path: '/orders/:id', component: OrdersShow, name: 'orders.show' },
    { path: '/orders/:id/edit', component: OrdersEdit, name: 'orders.edit' },
    /************************************************************* */


    //****************For Printer Company Panel********** */

    //*************Lots/Queue */
    { path: '/lots', component: LotsIndex, name: 'lots.index' },
    { path: '/checking-orders', component: CheckingOrdersIndex, name: 'checking_orders.index' },
    { path: '/printing-orders', component: PrintingOrdersIndex, name: 'printing_orders.index' },
    { path: '/printed-orders', component: PrintedOrdersIndex, name: 'printed_orders.index' },
    { path: '/finished-orders', component: FinishedOrdersIndex, name: 'finished_orders.index' },
    { path: '/all-orders', component: AllOrdersIndex, name: 'all_orders.index' },
    { path: '/printer-books/:id', component: PrinterBooksSingle, name: 'printer_books.show' },
    { path: '/printer-orders/:id', component: PrinterOrdersSingle, name: 'printer_orders.show' },
    { path: '/printer-virtual-boxes', component: VirtualBoxesIndex, name: 'virtual_boxes.index' },



    //*************************************************** */

    { path: '/permissions', component: PermissionsIndex, name: 'permissions.index' },
    { path: '/permissions/create', component: PermissionsCreate, name: 'permissions.create' },
    { path: '/permissions/:id', component: PermissionsShow, name: 'permissions.show' },
    { path: '/permissions/:id/edit', component: PermissionsEdit, name: 'permissions.edit' },
    { path: '/roles', component: RolesIndex, name: 'roles.index' },
    { path: '/roles/create', component: RolesCreate, name: 'roles.create' },
    { path: '/roles/:id', component: RolesShow, name: 'roles.show' },
    { path: '/roles/:id/edit', component: RolesEdit, name: 'roles.edit' },
    { path: '/users', component: UsersIndex, name: 'users.index' },
    { path: '/users/create', component: UsersCreate, name: 'users.create' },
    { path: '/users/:id', component: UsersShow, name: 'users.show' },
    { path: '/users/:id/edit', component: UsersEdit, name: 'users.edit' },

    { path: '/dashboard', component: Dashboard, name: 'dashboard' },

    { path: '*', redirect: { name: 'dashboard' } },
]

export default new VueRouter({
    mode: 'history',
    base: '/admin',
    routes
})