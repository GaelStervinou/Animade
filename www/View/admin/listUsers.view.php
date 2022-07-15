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
                                        <td><?php echo $user->getFirstName() ?></td>
                                        <td><?php echo $user->getLastName() ?></td>
                                        <td><?php echo $user->getEmail() ?></td>
                                        <td><?php echo $user->getStatus() ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($user->createdAt)) ?></td>
                                        <td><?php echo $user->getRoleId() ?></td>
                                        <?php if ($user->getRoleId() <= 2) : ?>
                                            <td><a href="/user/update?user_id=<?php echo $user->getId() ?>">Modifier</a></td>
                                        <?php else: ?>
                                            <td></td>
                                        <?php
                                        endif; ?>
                                        <?php if ($user->getRoleId() <= 2) : ?>
                                            <td><a href="/user/delete?user_id=<?php echo $user->getId() ?>">Supprimer</a></td>
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
                            $(document).ready(function() {
                                $('#table_id').DataTable({
                                    pagingType: 'full_numbers',
                                });
                            });
                        </script>
                        </div>
                    </div>
                </div>  
            </div>
        </section>
    </main>
