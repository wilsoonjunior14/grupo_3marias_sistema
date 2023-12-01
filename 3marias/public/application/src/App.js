import './App.css';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from './pages/login/Login';
import Home from './pages/home/Home';
import Index from './pages/index/Index';
import RecoveryPassword from './pages/recovery_password/Recovery';
import ChooseType from './pages/register/ChooseType';
import UserList from './pages/user/UserList';
import NoPage from './pages/no_page/NoPage';
import GroupList from './pages/group/GroupList';
import GroupForm from './pages/group/GroupForm';
import RolesList from './pages/roles/RolesList';
import CitiesList from './pages/cities/CitiesList';
import StatesList from './pages/states/StatesList';
import CountriesList from './pages/countries/CountriesList';
import CountriesForm from './pages/countries/ContriesForm';
import StatesForm from './pages/states/StatesForm';
import CitiesForm from './pages/cities/CitiesForm';
import RolesForm from './pages/roles/RolesForm';
import CategoriesList from './pages/categories/CategoriesList';
import CategoriesForm from './pages/categories/CategoriesForm';
import UserForm from './pages/user/UserForm';
import RegisterUser from './pages/register/user/RegisterUser';
import RegisterSuccess from './pages/register/RegisterSuccess';
import RegisterEnterprise from './pages/register/enterprise/RegisterEnterprise';
import EnterpriseList from './pages/enterprises/EnterpriseList';
import EnterpriseForm from './pages/enterprises/EnterpriseForm';
import GroupRoles from './pages/group/GroupRoles';

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

          <Route path='register' element={<ChooseType />}></Route>
          <Route path='register/user' element={<RegisterUser />}></Route>
          <Route path='register/enterprise' element={<RegisterEnterprise />}></Route>
          <Route path='register/success' element={<RegisterSuccess />}></Route>

          <Route path='enterprises' element={<EnterpriseList />}></Route>
          <Route path='enterprises/add' element={<EnterpriseForm />}></Route>
          <Route path='enterprises/edit/:id' element={<EnterpriseForm />}></Route>

          <Route path='users' element={<UserList />}></Route>
          <Route path='users/add' element={<UserForm />}></Route>
          <Route path='users/edit/:id' element={<UserForm />}></Route>

          <Route path='categories' element={<CategoriesList />}></Route>
          <Route path='categories/add' element={<CategoriesForm />}></Route>
          <Route path='categories/edit/:id' element={<CategoriesForm />}></Route>
          
          <Route path='groups' element={<GroupList />}></Route>
          <Route path='groups/add' element={<GroupForm />}></Route>
          <Route path='groups/edit/:id' element={<GroupForm />}></Route>
          <Route path='groups/roles/:id' element={<GroupRoles />}></Route>

          <Route path='roles' element={<RolesList />}></Route>
          <Route path='roles/add' element={<RolesForm />}></Route>
          <Route path='roles/edit/:id' element={<RolesForm />}></Route>

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
