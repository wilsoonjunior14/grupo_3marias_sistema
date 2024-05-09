import './App.css';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from './pages/login/Login';
import Home from './pages/home/Home';
import RecoveryPassword from './pages/recovery_password/Recovery';
import UserList from './pages/admin/user/UserList';
import NoPage from './pages/no_page/NoPage';
import GroupList from './pages/admin/group/GroupList';
import GroupForm from './pages/admin/group/GroupForm';
import RolesList from './pages/admin/roles/RolesList';
import CitiesList from './pages/admin/cities/CitiesList';
import StatesList from './pages/admin/states/StatesList';
import StatesForm from './pages/admin/states/StatesForm';
import CitiesForm from './pages/admin/cities/CitiesForm';
import RolesForm from './pages/admin/roles/RolesForm';
import UserForm from './pages/admin/user/UserForm';
import GroupRoles from './pages/admin/group/GroupRoles';
import EnterpriseDetails from './pages/admin/enterprises/EnterpriseDetails';
import EnterpriseForm from './pages/admin/enterprises/EnterpriseForm';
import ClientList from './pages/admin/clients/ClientList';
import ClientForm from './pages/admin/clients/ClientForm';
import ProductList from './pages/stocks/Products/ProductList';
import ProductForm from './pages/stocks/Products/ProductForm';
import AccountantsForm from './pages/admin/enterprises/AccountantsForm';
import EnterprisePartnerForm from './pages/admin/enterprises/EnterprisePartnerForm';
import EnterpriseOwnerForm from './pages/admin/enterprises/EnterpriseOwner';
import EnterpriseBranchForm from './pages/admin/enterprises/EnterpriseBranchForm';
import CategoryProductForm from './pages/stocks/CategoryProducts/CategoryProductForm';
import CategoryProductList from './pages/stocks/CategoryProducts/CategoryProductList';
import PartnerList from './pages/admin/partners/PartnerList';
import PartnerForm from './pages/admin/partners/PartnerForm';
import ProposalList from './pages/contracts/proposals/ProposalList';
import ProposalForm from './pages/contracts/proposals/ProposalForm';
import ClientDetails from './pages/admin/clients/ClientDetails';
import ProposalDownload from './pages/contracts/proposals/ProposalDownload';
import ContractList from './pages/contracts/ContractList';
import ContractForm from './pages/contracts/ContractForm';
import BillsReceiveList from './pages/money/BillsReceiveList';
import BillsReceiveForm from './pages/money/BillsReceiveForm';
import ProjectList from './pages/admin/projects/ProjectList';
import ProjectForm from './pages/admin/projects/ProjectForm';
import PurchaseOrdersList from './pages/money/PurchaseOrdersList';
import PurchaseOrdersForm from './pages/money/PurchaseOrdersForm';
import MoneyDashboard from './pages/money/MoneyDashboard';
import AccountForm from './pages/account/AccountForm';
import ServiceOrdersList from './pages/money/ServiceOrdersList';
import ServiceOrdersForm from './pages/money/ServiceOrdersForm';
import BillsPayList from './pages/money/BillsPayList';
import StockList from './pages/stocks/stocks/StockList';
import StockForm from './pages/stocks/stocks/StockForm';
import StockItems from './pages/stocks/stocks/StockItems';
import ServiceForm from './pages/stocks/services/ServiceForm';
import ServiceList from './pages/stocks/services/ServiceList';
import CategoryServiceList from './pages/stocks/categoryServices/CategoryServiceList';
import CategoryServiceForm from './pages/stocks/categoryServices/CategoryServiceForm';
import EngineerList from './pages/admin/engineers/EngineerList';
import EngineerForm from './pages/admin/engineers/EngineerForm';

console.disableYellowBox = true;

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/'>
          <Route index element={<Login />}></Route>
          <Route path='recovery' element={<RecoveryPassword />}></Route>
          <Route path='login' element={<Login />}></Route>
          <Route path='home' element={<Home />}></Route>

          <Route path='account' element={<AccountForm />}></Route>

          <Route path='admin/users' element={<UserList />}></Route>
          <Route path='admin/users/add' element={<UserForm />}></Route>
          <Route path='admin/users/edit/:id' element={<UserForm />}></Route>
          <Route path='admin/enterprises/:id' element={<EnterpriseDetails />}></Route>
          <Route path='admin/enterprises/edit/:id' element={<EnterpriseForm />}></Route>
          <Route path='admin/enterprises/accountants/edit/:id' element={<AccountantsForm />}></Route>
          <Route path='admin/enterprises/accountants/add/:enterpriseId' element={<AccountantsForm />}></Route>
          <Route path='admin/enterprises/enterprisePartners/add/:enterpriseId' element={<EnterprisePartnerForm />}></Route>
          <Route path='admin/enterprises/enterprisePartners/edit/:id' element={<EnterprisePartnerForm />}></Route>
          <Route path='admin/enterprises/enterpriseOwners/add/:enterpriseId' element={<EnterpriseOwnerForm />}></Route>
          <Route path='admin/enterprises/enterpriseOwners/edit/:id' element={<EnterpriseOwnerForm />}></Route>
          <Route path='admin/enterprises/enterpriseBranches/add/:enterpriseId' element={<EnterpriseBranchForm />}></Route>
          <Route path='admin/enterprises/enterpriseBranches/edit/:id' element={<EnterpriseBranchForm />}></Route>
          <Route path='admin/clients' element={<ClientList />}></Route>
          <Route path='admin/clients/add' element={<ClientForm disableHeader={false} />}></Route>
          <Route path='admin/clients/edit/:id' element={<ClientForm disableHeader={false} />}></Route>
          <Route path='admin/clients/details/:id' element={<ClientDetails />}></Route>
          <Route path='admin/groups' element={<GroupList />}></Route>
          <Route path='admin/groups/add' element={<GroupForm />}></Route>
          <Route path='admin/groups/edit/:id' element={<GroupForm />}></Route>
          <Route path='admin/groups/roles/:id' element={<GroupRoles />}></Route>
          <Route path='admin/roles' element={<RolesList />}></Route>
          <Route path='admin/roles/add' element={<RolesForm />}></Route>
          <Route path='admin/roles/edit/:id' element={<RolesForm />}></Route>
          <Route path='admin/cities' element={<CitiesList />}></Route>
          <Route path='admin/cities/add' element={<CitiesForm />}></Route>
          <Route path='admin/cities/edit/:id' element={<CitiesForm />}></Route>
          <Route path='admin/states' element={<StatesList />}></Route>
          <Route path='admin/states/add' element={<StatesForm />}></Route>
          <Route path='admin/states/edit/:id' element={<StatesForm />}></Route>
          <Route path='admin/partners' element={<PartnerList />}></Route>
          <Route path='admin/partners/add' element={<PartnerForm />}></Route>
          <Route path='admin/partners/edit/:id' element={<PartnerForm />}></Route>
          <Route path='admin/projects' element={<ProjectList />}></Route>
          <Route path='admin/projects/add' element={<ProjectForm />}></Route>
          <Route path='admin/projects/edit/:id' element={<ProjectForm />}></Route>
          <Route path='admin/engineers' element={<EngineerList />}></Route>
          <Route path='admin/engineers/add' element={<EngineerForm />}></Route>
          <Route path='admin/engineers/edit/:id' element={<EngineerForm />}></Route>

          <Route path='contracts/proposals' element={<ProposalList />}></Route>
          <Route path='contracts/proposals/add' element={<ProposalForm />}></Route>
          <Route path='contracts/proposals/edit/:id' element={<ProposalForm />}></Route> 
          <Route path='contracts/proposals/download/:id' element={<ProposalDownload />}></Route> 
          <Route path='contracts' element={<ContractList />}></Route>
          <Route path='contracts/add' element={<ContractForm />}></Route>
          <Route path='contracts/edit/:id' element={<ContractForm />}></Route> 

          <Route path='stocks' element={<StockList />}></Route>
          <Route path='stocks/add' element={<StockForm />}></Route> 
          <Route path='stocks/edit/:id' element={<StockForm />}></Route> 
          <Route path='stocks/items/:id' element={<StockItems />}></Route>
          <Route path='stocks/services' element={<ServiceList />}></Route>
          <Route path='stocks/services/add' element={<ServiceForm />}></Route>
          <Route path='stocks/services/edit/:id' element={<ServiceForm />}></Route>
          <Route path='stocks/categoryServices' element={<CategoryServiceList />}></Route>
          <Route path='stocks/categoryServices/add' element={<CategoryServiceForm />}></Route>
          <Route path='stocks/categoryServices/edit/:id' element={<CategoryServiceForm />}></Route>
          <Route path='stocks/products' element={<ProductList />}></Route>
          <Route path='stocks/products/add' element={<ProductForm />}></Route>
          <Route path='stocks/products/edit/:id' element={<ProductForm />}></Route>
          <Route path='stocks/categoryProducts' element={<CategoryProductList />}></Route>
          <Route path='stocks/categoryProducts/add' element={<CategoryProductForm />}></Route>
          <Route path='stocks/categoryProducts/edit/:id' element={<CategoryProductForm />}></Route>
          
          <Route path='money/dashboard' element={<MoneyDashboard />}></Route>
          <Route path='money/billsPay' element={<BillsPayList />}></Route>
          <Route path='money/billsReceive' element={<BillsReceiveList />}></Route>
          <Route path='money/billsReceive/details/:id' element={<BillsReceiveForm />}></Route>
          <Route path='money/purchaseOrders' element={<PurchaseOrdersList />}></Route>
          <Route path='money/purchaseOrders/add' element={<PurchaseOrdersForm />}></Route>
          <Route path='money/purchaseOrders/edit/:id' element={<PurchaseOrdersForm />}></Route>
          <Route path='money/serviceOrders' element={<ServiceOrdersList />}></Route>
          <Route path='money/serviceOrders/add' element={<ServiceOrdersForm />}></Route>
          <Route path='money/serviceOrders/edit/:id' element={<ServiceOrdersForm />}></Route>
          
          <Route path='*' element={<NoPage />}></Route>
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
