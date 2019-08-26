import Vue from 'vue'
import Vuex from 'vuex'
import Alert from './modules/alert'
import ChangePassword from './modules/change_password'
import Rules from './modules/rules'

import CourierCompaniesIndex from './admin-modules/CourierCompanies'
import CourierCompaniesSingle from './admin-modules/CourierCompanies/single'

import CourierRoutersIndex from './admin-modules/CourierRouters'
import CourierRoutersSingle from './admin-modules/CourierRouters/single'

import PrinterCompaniesIndex from './admin-modules/PrinterCompanies'
import PrinterCompaniesSingle from './admin-modules/PrinterCompanies/single'

import PrinterRoutersIndex from './admin-modules/PrinterRouters'
import PrinterRoutersSingle from './admin-modules/PrinterRouters/single'

import OrdersIndex from './admin-modules/Orders'
import OrdersSingle from './admin-modules/Orders/single'

import PrinterLotsIndex from './admin-modules/Lots'
import PrinterOrdersIndex from './admin-modules/PrinterOrders'
import PrinterOrdersSingle from './admin-modules/PrinterOrders/single'

import VirtualBoxesIndex from './admin-modules/VirtualBoxes'

import PermissionsIndex from './admin-modules/Permissions'
import PermissionsSingle from './admin-modules/Permissions/single'
import RolesIndex from './admin-modules/Roles'
import RolesSingle from './admin-modules/Roles/single'
import UsersIndex from './admin-modules/Users'
import UsersSingle from './admin-modules/Users/single'
import ImageFileInput from './modules/image_file_input'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        Alert,
        ChangePassword,
        Rules,
        CourierCompaniesIndex,
        CourierCompaniesSingle,
        CourierRoutersIndex,
        CourierRoutersSingle,
        PermissionsIndex,
        PermissionsSingle,
        RolesIndex,
        RolesSingle,
        UsersIndex,
        UsersSingle,
        ImageFileInput,
        PrinterCompaniesIndex,
        PrinterCompaniesSingle,
        PrinterRoutersIndex,
        PrinterRoutersSingle,
        OrdersIndex,
        OrdersSingle,
        PrinterLotsIndex,
        PrinterOrdersIndex,
        PrinterOrdersSingle,
        VirtualBoxesIndex
    },
    strict: debug,
})