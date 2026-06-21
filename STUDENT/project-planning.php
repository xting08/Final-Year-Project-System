<section id="planning">
                <div class="title-button">
                    <h2>Project Planning</h2>
                    <label for="add-plan" class="add-plan-button">ADD PLAN</label>
                </div>

               <hr/> 
                <div id="plan-display">
                    <?php $project -> displayTodo() ?>
                </div>
            </section>
            <input type="checkbox" id="add-plan" hidden>
            <section id="add-plan-section">
                <form id="add-plan-form" method="POST" action="project-student.php">
                    <label for="add-plan" class="close-plan">
                        <i class="fa-regular fa-circle-xmark"></i>
                    </label>
                    <h1>ADD PLAN</h1>
                    <select name="add-week" id="add-week">
                        <option value="" disabled selected>Select Week</option>
                        <?php
                            for ($i = 1; $i <= 14; $i++){
                                echo "<option value='$i'>WEEK $i</option>";
                            }    
                        ?>
                    </select>
                    <input type="text" name="add-title" id="add-title" placeholder="Add Title">
                    <!-- <input type="text" name="add" id="add" placeholder="+"> -->
                    <button type="submit" name="submit">ADD</button>
                </form>
            </section>
            