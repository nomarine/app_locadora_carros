<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início card pesquisa -->
                <card-component titulo="Pesquisa por marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputId" 
                                    label="ID da Marca" 
                                    ajuda-id="ajudaId" 
                                    ajuda-texto="Opcional. Informe o ID da marca. Apenas números."
                                >
                                    <input type="number" class="form-control" id="inputId" aria-describedby="ajudaId">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputNome" 
                                    label="Nome da Marca" 
                                    ajuda-id="ajudaNome" 
                                    ajuda-texto="Opcional. Informe o nome da marca."
                                >
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="ajudaNome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <!-- final card pesquisa -->

                <!-- início card resultado -->
                <card-component titulo="Marcas encontradas">
                    <template v-slot:conteudo>                        
                        <table-component></table-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Adicionar</button>
                    </template>
                </card-component>
                <!-- final card resultado -->
            </div>
        </div>
    </div>
</template>

<script>

</script>
