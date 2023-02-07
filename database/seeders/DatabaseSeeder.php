<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Feature;
use App\Models\User;
use Database\Factories\CommentFactory;
use Database\Factories\CompanyFactory;
use Database\Factories\CustomerFactory;
use Database\Factories\FeatureFactory;
use Database\Factories\LoginFactory;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed companies and users
        CompanyFactory::new()
            ->count(5000)
            ->create()
            ->each(
                fn (Company $company) => $company
                    ->users()
                    ->createMany(
                        UserFactory::new()->count(10)->make()->map->getAttributes()
                    )
            );

        // seed posts
        User::query()
            ->inRandomOrder()
            ->take(50)
            ->get()
            ->each(
                fn(User $user) => $user->posts()->createMany(
                    PostFactory::new()->count(5)->make()->toArray()
                )
            );

        // seed logins
        User::query()
            ->inRandomOrder()
            ->take(10000)
            ->get()
            ->each(
                fn(User $user) => $user
                    ->logins()
                    ->createMany(
                        LoginFactory::new()->count(5)->make()->toArray()
                    )
            );

        // seed customers
        User::query()
            ->inRandomOrder()
            ->take(10000)
            ->get()
            ->each(
                fn(User $user) => $user
                    ->customers()
                    ->createMany(
                        CustomerFactory::new()->count(5)->make()->toArray()
                    )
            );

        // seed features and comments
        $randomUsers = User::query()->inRandomOrder()->limit(250)->get();
        FeatureFactory::new()->count(60)->create()->each(function(Feature $feature) use($randomUsers) {
            $feature->comments()->createMany(
                CommentFactory
                    ::new()
                    ->count(rand(1, 50))
                    ->make()
                    ->each(function(Comment $comment) use($randomUsers) {
                        $comment->user_id = $randomUsers->random()->id;
                    })
                    ->toArray()
            );
        });

        // specific seeds
        /** @var \App\Models\User $user */
        $user = User::query()->find(10000);
        $user->update([
            'first_name' => 'Bill',
            'last_name' => 'Gates',
            'email' => 'bill.gates@microsoft.com',
        ]);
        $user->company->update([
            'name' => 'Microsoft Corporation',
        ]);

        /** @var \App\Models\User $user */
        $user = User::query()->find(20000);
        $user->update([
            'first_name' => 'Tim',
            'last_name' => 'O\'Reilly',
            'email' => 'tim@oreilly.com',
        ]);
        $user->company->update([
            'name' => 'O\'Reilly Media Inc.',
        ]);

        $user1 = UserFactory::new()
            ->create([
                'first_name' => 'Ted',
                'last_name' => 'Bossman',
                'is_owner' => true,
                'gender' => 0,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);
        $user2 = UserFactory::new()
            ->create([
                'first_name' => 'Sarah',
                'last_name' => 'Seller',
                'gender' => 1,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);
        $user3 = UserFactory::new()
            ->create([
                'first_name' => 'Chase',
                'last_name' => 'Indeals',
                'gender' => 0,
                'company_id' => Company::query()->inRandomOrder()->first()->id,
            ]);

        foreach ([$user1, $user2, $user3] as $us) {
            $us->customers()
                ->createMany(
                    CustomerFactory::new()->count(25)->make()->toArray()
                );
        }

        // seed books
        $users = User::query()->inRandomOrder()->take(100)->get();
        $this->getBooks()->map(
            fn(array $book) => Book::unguarded(
                fn() => Book::query()->create($book)
            )
        )->each(function(Book $book) use ($users) {
            $users->random(rand(1, 5))->each(function(User $user) use ($book) {
                $user->books()->attach($book, [
                    'borrowed_date' => fake()->dateTimeBetween('-5 years'),
                ]);
            });
        });
    }

    protected function getBooks()
    {
        return collect([
            ['name' => 'The Pragmatic Programmer: From Journeyman to Master', 'author' => 'Andy Hunt'],
            ['name' => 'The C Programming Language', 'author' => 'Brian W. Kernighan'],
            ['name' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'author' => 'Robert C. Martin'],
            ['name' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'author' => 'Erich Gamma'],
            ['name' => 'Code Complete', 'author' => 'Steve McConnell'],
            ['name' => 'Introduction to Algorithms', 'author' => 'Thomas H. Cormen'],
            ['name' => 'Structure and Interpretation of Computer Programs', 'author' => 'Harold Abelson'],
            ['name' => 'Refactoring: Improving the Design of Existing Code', 'author' => 'Martin Fowler'],
            ['name' => 'The Mythical Man-Month: Essays on Software Engineering', 'author' => 'Frederick P. Brooks Jr.'],
            ['name' => 'The Art of Computer Programming, Volumes 1-3 Boxed Set', 'author' => 'Donald Ervin Knuth'],
            ['name' => 'The C++ Programming Language', 'author' => 'Bjarne Stroustrup'],
            ['name' => 'The Clean Coder: A Code of Conduct for Professional Programmers', 'author' => 'Robert C. Martin'],
            ['name' => 'JavaScript: The Good Parts', 'author' => 'Douglas Crockford'],
            ['name' => 'Head First Design Patterns', 'author' => 'Eric Freeman'],
            ['name' => 'Working Effectively with Legacy Code', 'author' => 'Michael C. Feathers'],
            ['name' => 'Compilers: Principles, Techniques, and Tools', 'author' => 'Alfred V. Aho'],
            ['name' => 'The Little Schemer', 'author' => 'Daniel P. Friedman'],
            ['name' => 'Modern Operating Systems', 'author' => 'Andrew S. Tanenbaum'],
            ['name' => 'Test Driven Development: By Example', 'author' => 'Kent Beck'],
            ['name' => 'The UNIX Programming Environment', 'author' => 'Brian W. Kernighan'],
            ['name' => 'Algorithms', 'author' => 'Robert Sedgewick'],
            ['name' => 'Head First Java', 'author' => 'Kathy Sierra'],
            ['name' => 'xUnit Test Patterns: Refactoring Test Code', 'author' => 'Gerard Meszaros'],
            ['name' => 'Domain-Driven Design: Tackling Complexity in the Heart of Software', 'author' => 'Eric Evans'],
            ['name' => 'Agile Software Development, Principles, Patterns, and Practices', 'author' => 'Robert C. Martin'],
            ['name' => 'Growing Object-Oriented Software, Guided by Tests', 'author' => 'Steve  Freeman'],
            ['name' => 'Patterns of Enterprise Application Architecture', 'author' => 'Martin Fowler'],
            ['name' => 'Algorithm Design Manual', 'author' => 'Steve S. Skiena'],
            ['name' => 'Effective C++: 55 Specific Ways to Improve Your Programs and Designs', 'author' => 'Scott Meyers'],
            ['name' => 'Programming Ruby: The Pragmatic Programmers\' Guide', 'author' => 'Dave Thomas'],
            ['name' => 'Real World Haskell: Code You Can Believe In', 'author' => 'Bryan O\'Sullivan'],
            ['name' => 'Artificial Intelligence: A Modern Approach', 'author' => 'Stuart Russell'],
            ['name' => 'Learning Python', 'author' => 'Mark Lutz'],
            ['name' => 'Learning Perl', 'author' => 'Randal L. Schwartz'],
            ['name' => 'How to Design Programs: An Introduction to Programming and Computing', 'author' => 'Matthias Felleisen'],
            ['name' => 'The Practice of Programming (Addison-Wesley Professional Computing Series)', 'author' => 'Brian W. Kernighan'],
            ['name' => 'Joel on Software', 'author' => 'Joel Spolsky'],
            ['name' => 'Advanced Programming in the UNIX Environment', 'author' => 'Stephen A. Rago'],
            ['name' => 'Programming Perl', 'author' => 'Tom Christiansen'],
            ['name' => 'The Elements of Style', 'author' => 'William Strunk Jr.'],
            ['name' => 'Unix Network Programming, Volume 1: Networking APIs - Sockets and XTI', 'author' => 'W. Richard Stevens'],
            ['name' => 'Learn You a Haskell for Great Good!: A Beginner\'s Guide', 'author' => 'Miran Lipovača'],
            ['name' => 'The RSpec Book', 'author' => 'David Chelimsky'],
            ['name' => 'Concrete Mathematics: A Foundation for Computer Science', 'author' => 'Ronald L. Graham'],
            ['name' => 'ANSI Common Lisp', 'author' => 'Paul Graham'],
            ['name' => 'Ship It!', 'author' => 'Jared  Richardson'],
            ['name' => 'Accelerated C++: Practical Programming by Example', 'author' => 'Andrew Koenig'],
            ['name' => 'The Rails Way', 'author' => 'Obie Fernandez'],
            ['name' => 'Expert C Programming: Deep C Secrets', 'author' => 'Peter van der Linden'],
            ['name' => 'Release It!: Design and Deploy Production-Ready Software', 'author' => 'Michael T. Nygard'],
            ['name' => 'Refactoring to Patterns', 'author' => 'Joshua Kerievsky'],
            ['name' => 'C# in Depth', 'author' => 'Jon Skeet'],
            ['name' => '97 Things Every Programmer Should Know: Collective Wisdom from the Experts', 'author' => 'Kevlin Henney'],
            ['name' => 'Object-Oriented Software Construction (Book/CD-ROM)', 'author' => 'Bertrand Meyer'],
            ['name' => 'Agile Web Development with Rails: A Pragmatic Guide', 'author' => 'Dave Thomas'],
            ['name' => 'Essentials of Programming Languages', 'author' => 'Daniel P. Friedman'],
            ['name' => 'Fundamental Kotlin', 'author' => 'Miloš  Vasić'],
            ['name' => 'The Art of Unit Testing: With Examples in .NET', 'author' => 'Roy Osherove'],
            ['name' => 'Eloquent JavaScript: A Modern Introduction to Programming', 'author' => 'Marijn Haverbeke'],
            ['name' => 'Extreme Programming Explained: Embrace Change (The XP Series)', 'author' => 'Kent Beck'],
            ['name' => 'Introduction to the Theory of Computation', 'author' => 'Michael Sipser'],
            ['name' => 'Assembly Language: Step-By-Step', 'author' => 'Jeff Duntemann'],
            ['name' => 'Specification by Example: How Successful Teams Deliver the Right Software', 'author' => 'Gojko Adzic'],
            ['name' => 'The Agile Samurai', 'author' => 'Jonathan Rasmusson'],
            ['name' => 'Lean from the Trenches', 'author' => 'Henrik Kniberg'],
            ['name' => 'C++ Standard Library: A Tutorial and Reference', 'author' => 'Nicolai M. Josuttis'],
            ['name' => 'The Visual Display of Quantitative Information', 'author' => 'Edward R. Tufte'],
            ['name' => 'Sams Teach Yourself MySQL in 24 Hours', 'author' => 'Julie C. Meloni'],
            ['name' => 'PHP and MySQL Web Development', 'author' => 'Luke Welling'],
            ['name' => 'UNIX Network Programming, Volume 2: Interprocess Communications', 'author' => 'W. Richard Stevens'],
            ['name' => 'Practical Object Oriented Design in Ruby', 'author' => 'Sandi Metz'],
            ['name' => 'Writing Solid Code', 'author' => 'Steve Maguire'],
            ['name' => 'Continuous Delivery: Reliable Software Releases Through Build, Test, and Deployment Automation', 'author' => 'Jez Humble'],
            ['name' => 'Hacker\'s Delight', 'author' => 'Henry S. Warren Jr.'],
            ['name' => 'Beginning Java 2', 'author' => 'Ivor Horton'],
            ['name' => 'Haskell: The Craft of Functional Programming', 'author' => 'Simon Thompson'],
            ['name' => 'Learning Ruby', 'author' => 'Michael J.  Fitzgerald'],
            ['name' => 'Sams Teach Yourself JavaScript in 24 Hours', 'author' => 'Michael Moncur'],
            ['name' => 'Peopleware: Productive Projects and Teams', 'author' => 'Tom DeMarco'],
            ['name' => 'Think Like a Programmer: An Introduction to Creative Problem Solving', 'author' => 'V. Anton Spraul'],
            ['name' => 'Dependency Injection in .NET', 'author' => 'Mark Seemann'],
            ['name' => 'Object-Oriented Analysis and Design with Applications', 'author' => 'Grady Booch'],
            ['name' => 'The Goal: A Process of Ongoing Improvement', 'author' => 'Eliyahu M. Goldratt'],
            ['name' => 'Make Your Own Neural Network: An In-depth Visual Introduction For Beginners', 'author' => 'Michael Taylor'],
            ['name' => 'Scrum and XP from the Trenches', 'author' => 'Henrik Kniberg'],
            ['name' => 'Slack: Getting Past Burnout, Busywork, and the Myth of Total Efficiency', 'author' => 'Tom DeMarco'],
            ['name' => 'The Software Craftsman: Professionalism, Pragmatism, Pride', 'author' => 'Sandro Mancuso'],
            ['name' => 'Implementing Lean Software Development: From Concept to Cash', 'author' => 'Mary Poppendieck'],
            ['name' => 'Engineering a Compiler', 'author' => 'Keith D. Cooper'],
            ['name' => 'Reviewing C++', 'author' => 'Alex Maureau'],
            ['name' => 'Sams Teach Yourself Perl in 24 Hours', 'author' => 'Clinton Pierce'],
            ['name' => 'C: A Reference Manual', 'author' => 'Samuel P. Harbison III'],
            ['name' => 'STL Tutorial and Reference Guide: C++ Programming with the Standard Template Library', 'author' => 'David R. Musser'],
            ['name' => 'Manage Your Project Portfolio', 'author' => 'Johanna Rothman'],
            ['name' => 'Thinking Forth', 'author' => 'Leo Brodie'],
            ['name' => 'Simulation of Digital Communication Systems using Matlab', 'author' => 'Mathuranathan Viswanathan'],
            ['name' => 'Your First App: Node.js', 'author' => 'Jim Schubert'],
            ['name' => 'The Scrumban [R]Evolution: Getting the Most Out of Agile, Scrum, and Lean Kanban', 'author' => 'Ajay Reddy'],
            ['name' => 'Code Reviews 101', 'author' => 'Giuliana Carullo'],
            ['name' => 'Python Crash Course: A Hands-On, Project-Based Introduction to Programming', 'author' => 'Eric Matthes'],
        ]);
    }
}
