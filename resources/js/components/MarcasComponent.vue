<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Marcas</div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col mb-3">
                                <label for="inputId" class="form-label">ID</label>
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp">
                                <div id="idHelp" class="form-text text-muted">Opcional.</div>
                            </div>

                            <div class="col mb-3">
                                <label for="inputNome" class="form-label">Nome da Marca</label>
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                                <div id="nomeHelp" class="form-text text-muted">Opcional.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

</script>
