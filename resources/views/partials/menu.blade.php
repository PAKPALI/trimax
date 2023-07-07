
  @if (Auth::user()->type_user ==1)
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
            <a href="{{route('profil')}}" class="d-block">{{ strtoupper(Auth::user()->nom)}}</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            @if( Route::currentRouteName() == "tableau")
              <li class="nav-item menu">
                <a href="{{route('tableau')}}" class="nav-link active">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Tableau de bord
                  </p>
                </a>
              </li>
            @else
              <li class="nav-item menu">
                <a href="{{route('tableau')}}" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Tableau de bord
                  </p>
                </a>
              </li>
            @endif
            <!-- gerer les pays -->
            <li class="nav-header"> GESTION PAYS</li>
            @if( Route::currentRouteName() == "pays")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
            @else
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
            @endif

            <!-- gerer les banques-->
            <li class="nav-header"> GESTION BANQUE</li>
            @if( Route::currentRouteName() == "banque")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
            @else
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
            @endif

            <!-- gerer les banques-->
            <li class="nav-header"> GESTION TYPE DE DEPENSE</li>
            @if( Route::currentRouteName() == "typeDepense")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
            @else
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
            @endif

            <!-- gerer la caisse -->
            <li class="nav-header"> GESTION CAISSE</li>
            @if( Route::currentRouteName() == "caisse.depot" || Route::currentRouteName() == "caisse.retrait" || Route::currentRouteName() == "caisse.operation")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
                      <p>Entrée</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a  href="{{route('caisse.retrait')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Sortie</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('caisse.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique opérations</p>
                    </a>
                  </li>
                </ul>
              </li>
            @else
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
                      <p>Entrée</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a  href="{{route('caisse.retrait')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Sortie</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('caisse.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique opérations</p>
                    </a>
                  </li>
                </ul>
              </li>
            @endif

            <!-- gerer la sous caisse -->
            <li class="nav-header"> GESTION SOUS-CAISSE</li>
            @if( Route::currentRouteName() == "sous_caisse" || Route::currentRouteName() == "sous_caisse.demande_depense" || Route::currentRouteName() == "sous_caisse.operation" || Route::currentRouteName() == "sous_caisse.operation_depense")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
                    <a href="{{route('sous_caisse.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique opérations</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('sous_caisse.operation_depense')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique dépenses</p>
                    </a>
                  </li>
                </ul>
              </li>
            @else
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
                    <a href="{{route('sous_caisse.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique opérations</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('sous_caisse.operation_depense')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Historique dépenses</p>
                    </a>
                  </li>
                </ul>
              </li>
            @endif

            <!-- gerer les utilisateurs -->
            <li class="nav-header"> GESTION UTILISATEURS</li>
            @if( Route::currentRouteName() == "user")
              <li class="nav-item">
                <a href="#" class="nav-link active">
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
                </ul>
              </li>
            @else
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
            @endif

            <!-- gerer les clients -->
            <li class="nav-header"> GESTION DES CLIENTS</li>
            @if( Route::currentRouteName() == "client.accueil" || Route::currentRouteName() == "client" || Route::currentRouteName() == "client.pret" || Route::currentRouteName() == "client.remb" || Route::currentRouteName() == "client.operation")
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>
                    CLIENTS
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{route('client.accueil')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Acceuil</p>
                      </a>
                    </li>
                  <li class="nav-item">
                    <a href="{{route('client')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ajouter client</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.pret')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Credit accordé</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.remb')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Remboursement</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>operation sur client</p>
                    </a>
                  </li>
                </ul>
              </li>
            @else
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
                      <a href="{{route('client.accueil')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Acceuil</p>
                      </a>
                    </li>
                  <li class="nav-item">
                    <a href="{{route('client')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ajouter client</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.pret')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Credit accordé</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.remb')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Remboursement</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('client.operation')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>operation sur client</p>
                    </a>
                  </li>
                </ul>
              </li>
            @endif
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
  @else
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
            <a href="{{route('profil')}}" class="d-block">{{ strtoupper(Auth::user()->nom)}}</a>
          </div>
        </div>
        @if (Auth::user()->connected == 1)
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            @if (Auth::user()->sous_caisse_id ==null)
            @else
              @if( Route::currentRouteName() == "tableau")
                <li class="nav-item menu">
                  <a href="{{route('tableau')}}" class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                      Tableau de bord
                    </p>
                  </a>
                </li>
              @else
                <li class="nav-item menu">
                  <a href="{{route('tableau')}}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                      Tableau de bord
                    </p>
                  </a>
                </li>
              @endif
              <!-- gerer la sous caisse -->
              <li class="nav-header"> GESTION SOUS-CAISSE</li>
              @if( Route::currentRouteName() == "sous_caisse" || Route::currentRouteName() == "sous_caisse.demande_depense" || Route::currentRouteName() == "sous_caisse.operation" || Route::currentRouteName() == "sous_caisse.operation_depense")
                <li class="nav-item">
                  <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                      Sous-caisse
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('sous_caisse.demande_depense')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Demande Dépense</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('sous_caisse.operation')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Historique opérations</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('sous_caisse.operation_depense')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Historique dépenses</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @else
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
                      <a href="{{route('sous_caisse.demande_depense')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Demande Dépense</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('sous_caisse.operation')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Historique opérations</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('sous_caisse.operation_depense')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Historique dépenses</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif
            @endif

            @if (Auth::user()->status_client ==1)
              <!-- gerer les clients -->
              <li class="nav-header"> GESTION DES CLIENTS</li>
              @if( Route::currentRouteName() == "client.accueil" || Route::currentRouteName() == "client" || Route::currentRouteName() == "client.pret" || Route::currentRouteName() == "client.remb" || Route::currentRouteName() == "client.operation")
                <li class="nav-item">
                  <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                      CLIENTS
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('client.accueil')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Acceuil</p>
                        </a>
                      </li>
                    <li class="nav-item">
                      <a href="{{route('client')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter client</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.pret')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Credit accordé</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.remb')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Remboursement</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.operation')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>operation sur client</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @else
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
                        <a href="{{route('client.accueil')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Acceuil</p>
                        </a>
                      </li>
                    <li class="nav-item">
                      <a href="{{route('client')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Credit accordé</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.pret')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Demande de pret</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.remb')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Remboursement</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('client.operation')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>operation sur client</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif
            @endif
          </ul>
        </nav>
        @endif
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
  @endif