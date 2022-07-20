    <main>
        <section id="login-form">
            <div class="grid">
                <div id="login-form">
                    <div class="row">
                        <div class="table">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th>Date de création</th>
                                    <th>Rôle</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><?= $user->getFirstName() ?></td>
                                        <td><?= $user->getLastName() ?></td>
                                        <td><?= $user->getEmail() ?></td>
                                        <td><?= $user->getStatus() ?></td>
                                        <td><?= \App\Helpers\Formalize::formalizeDateYearMonthDay($user->createdAt) ?></td>
                                        <td><?= $user->getRoleId() ?></td>
                                        <?php if ($user->getRoleId() <= 2 || \App\Core\Security::canAsSuperAdmin() === true) : ?>
                                            <td><a href="/user/update?user_id=<?= $user->getId() ?>">Modifier</a></td>
                                        <?php else: ?>
                                            <td></td>
                                        <?php
                                        endif; ?>
                                        <?php if (($user->getRoleId() <= 2 || \App\Core\Security::canAsSuperAdmin() === true) && $user->getStatus() !== -1) :
                                            ?>
                                            <td><a href="/user/delete?user_id=<?= $user->getId() ?>">Supprimer</a></td>
                                        <?php
                                        else: ?>
                                            <td></td>
                                        <?php
                                        endif; ?>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot >
                                <tr class="tfoot">
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th>Date de création</th>
                                    <th>Rôle</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>


                            <script>
                                updateDataTable();
                            </script>
                        </div>
                    </div>
                </div>  
            </div>
        </section>
    </main>
