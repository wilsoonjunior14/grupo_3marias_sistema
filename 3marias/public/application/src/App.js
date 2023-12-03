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
import CitiesList from './pages/cities/CitiesList';
import StatesList from './pages/states/StatesList';
import CountriesList from './pages/countries/CountriesList';
import CountriesForm from './pages/countries/ContriesForm';
import StatesForm from './pages/states/StatesForm';
import CitiesForm from './pages/cities/CitiesForm';
import RolesForm from './pages/admin/roles/RolesForm';
import UserForm from './pages/admin/user/UserForm';
import GroupRoles from './pages/admin/group/GroupRoles';
import EnterpriseDetails from './pages/admin/enterprises/EnterpriseDetails';
import TiposDocumentos from './pages/admin/tiposDocumentos/TiposDocumentos';
import ContractsModel from './pages/admin/contractsModel/ContractsModel';
import ContractsModelForm from './pages/admin/contractsModel/ContractsModelForm';

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

          <Route path='admin/users' element={<UserList />}></Route>
          <Route path='admin/users/add' element={<UserForm />}></Route>
          <Route path='admin/users/edit/:id' element={<UserForm />}></Route>
          <Route path='admin/enterprises' element={<EnterpriseDetails />}></Route>
          <Route path='admin/documents' element={<TiposDocumentos />}></Route>
          <Route path='admin/contractsModel' element={<ContractsModel />}></Route>
          <Route path='admin/contractsModel/add' element={<ContractsModelForm />}></Route>

          <Route path='admin/groups' element={<GroupList />}></Route>
          <Route path='admin/groups/add' element={<GroupForm />}></Route>
          <Route path='admin/groups/edit/:id' element={<GroupForm />}></Route>
          <Route path='admin/groups/roles/:id' element={<GroupRoles />}></Route>

          <Route path='admin/roles' element={<RolesList />}></Route>
          <Route path='admin/roles/add' element={<RolesForm />}></Route>
          <Route path='admin/roles/edit/:id' element={<RolesForm />}></Route>

          <Route path='cities' element={<CitiesList />}></Route>
          <Route path='cities/add' element={<CitiesForm />}></Route>
          <Route path='cities/edit/:id' element={<CitiesForm />}></Route>

          <Route path='states' element={<StatesList />}></Route>
          <Route path='states/add' element={<StatesForm />}></Route>
          <Route path='states/edit/:id' element={<StatesForm />}></Route>

          <Route path='countries' element={<CountriesList />}></Route>
          <Route path='countries/add' element={<CountriesForm />}></Route>
          <Route path='countries/edit/:id' element={<CountriesForm />}></Route>
          
          <Route path='*' element={<NoPage />}></Route>
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
