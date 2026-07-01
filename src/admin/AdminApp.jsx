import DashboardPage from './pages/DashboardPage.jsx';
import CommitteesDetailPage from './pages/CommitteesDetailPage.jsx';
import CommitteesListPage from './pages/CommitteesListPage.jsx';
import EventsDetailPage from './pages/EventsDetailPage.jsx';
import EventsListPage from './pages/EventsListPage.jsx';
import FormsBuilderPage from './pages/FormsBuilderPage.jsx';
import FormsListPage from './pages/FormsListPage.jsx';
import FormsSubmissionsPage from './pages/FormsSubmissionsPage.jsx';
import MembersPage from './pages/MembersPage.jsx';
import SuperAdminAppsPage from './pages/SuperAdminAppsPage.jsx';
import SuperAdminLogsPage from './pages/SuperAdminLogsPage.jsx';
import SuperAdminDepartmentsPage from './pages/SuperAdminDepartmentsPage.jsx';
import SuperAdminUsersPage from './pages/SuperAdminUsersPage.jsx';
import PanelSelectPage from './pages/PanelSelectPage.jsx';
import DeptDashboardPage from './pages/DeptDashboardPage.jsx';
import ManagementReportsPage from './pages/ManagementReportsPage.jsx';
import ManagementAnnouncePage from './pages/ManagementAnnouncePage.jsx';
import ManagementDirectoryPage from './pages/ManagementDirectoryPage.jsx';
import FinancePage from './pages/FinancePage.jsx';
import DuesPage from './pages/DuesPage.jsx';

const fallbackBoot = {
  page: { title: 'Admin Panel', subtitle: '' },
  admin: { name: 'Admin', role: 'admin' },
  allowedApps: [],
  currentRoute: '/admin/dashboard',
  baseUrl: '',
  assets: {},
};

function readBootData() {
  const node = document.getElementById('admin-boot');

  if (!node?.textContent) {
    return fallbackBoot;
  }

  try {
    return { ...fallbackBoot, ...JSON.parse(node.textContent) };
  } catch (error) {
    console.error('Admin boot data could not be parsed.', error);
    return fallbackBoot;
  }
}

export default function AdminApp() {
  const boot = readBootData();

  if (boot.currentRoute === '/superadmin/users') {
    return <SuperAdminUsersPage boot={boot} />;
  }

  if (boot.currentRoute === '/superadmin/apps') {
    return <SuperAdminAppsPage boot={boot} />;
  }

  if (boot.currentRoute === '/superadmin/logs') {
    return <SuperAdminLogsPage boot={boot} />;
  }

  if (boot.currentRoute === '/superadmin/departments') {
    return <SuperAdminDepartmentsPage boot={boot} />;
  }

  if (boot.currentRoute?.startsWith('/admin/members')) {
    return <MembersPage boot={boot} />;
  }

  if (boot.currentRoute?.startsWith('/admin/committees/') && boot.currentRoute !== '/admin/committees') {
    return <CommitteesDetailPage boot={boot} />;
  }

  if (boot.currentRoute === '/admin/committees') {
    return <CommitteesListPage boot={boot} />;
  }

  if (boot.currentRoute?.startsWith('/admin/events/') && boot.currentRoute !== '/admin/events') {
    return <EventsDetailPage boot={boot} />;
  }

  if (boot.currentRoute === '/admin/events') {
    return <EventsListPage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/edit') && boot.currentRoute?.startsWith('/admin/forms/')) {
    return <FormsBuilderPage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/submissions') && boot.currentRoute?.startsWith('/admin/forms/')) {
    return <FormsSubmissionsPage boot={boot} />;
  }

  if (boot.currentRoute === '/admin/forms') {
    return <FormsListPage boot={boot} />;
  }

  if (boot.currentRoute === '/panel/select') {
    return <PanelSelectPage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/management/reports') && boot.currentRoute?.startsWith('/panel/')) {
    return <ManagementReportsPage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/management/announce') && boot.currentRoute?.startsWith('/panel/')) {
    return <ManagementAnnouncePage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/management/directory') && boot.currentRoute?.startsWith('/panel/')) {
    return <ManagementDirectoryPage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/finance') && boot.currentRoute?.startsWith('/panel/')) {
    return <FinancePage boot={boot} />;
  }

  if (boot.currentRoute?.endsWith('/dues') && boot.currentRoute?.startsWith('/panel/')) {
    return <DuesPage boot={boot} />;
  }

  // Dashboard for /panel/{slug} but NOT /panel/{slug}/forms etc.
  if (boot.currentRoute?.startsWith('/panel/') && boot.currentRoute.split('/').length === 3) {
    return <DeptDashboardPage boot={boot} />;
  }

  return <DashboardPage boot={boot} />;
}
