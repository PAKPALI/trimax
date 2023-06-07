  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('img/trimax.gif')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8" height="100" width="100">
      <span class="brand-text font-weight-light"><h3>TRIMAX</h3></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('admin/man/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">PAKPALI didier</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Tableau de bord
              </p>
            </a>
          </li>
          <!-- gerer les pays -->
          <li class="nav-header"> GESTION PAYS</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Pays
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('pays')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter pays</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer les banques-->
          <li class="nav-header"> GESTION BANQUE</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Banque
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('banque')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter banque</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer les banques-->
          <li class="nav-header"> GESTION TYPE DE DEPENSE</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Type de depense
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('typeDepense')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter type depense</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer la caisse -->
          <li class="nav-header"> GESTION CAISSE</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Caisse principale
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('caisse.depot')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dépot</p>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{route('caisse.retrait')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Retrait</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Historique opérations</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer la sous caisse -->
          <li class="nav-header"> GESTION SOUS-CAISSE</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                Sous-caisse
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('sous_caisse')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter sous caisse</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sous_caisse.demande_depense')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Demande Dépense</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sous_caisse.historique')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Historique opérations</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Historique dépenses</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer les utilisateurs -->
          <li class="nav-header"> GESTION UTILISATEURS</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Utilisateurs
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('user')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter des utilisateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gérer des utilisateur</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- gerer les clients -->
          <li class="nav-header"> GESTION LES CLIENTS</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                CLIENTS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter client</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>operation sur client</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>