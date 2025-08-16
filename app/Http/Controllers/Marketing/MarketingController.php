<?php

namespace App\Http\Controllers\Marketing;

use App\Action\Marketing\GenerateSocialPostAction;
use App\Action\Marketing\GenerateStrategyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketing\StoreKanbanBoardRequest;
use App\Http\Requests\Marketing\StoreKanbanTaskRequest;
use App\Http\Requests\Marketing\StoreMarketingStrategyRequest;
use App\Http\Requests\Marketing\StoreMindMapRequest;
use App\Http\Requests\Marketing\StoreMindNodeRequest;
use App\Http\Requests\Marketing\StoreSocialMediaPostRequest;
use App\Http\Requests\Marketing\UpdateKanbanTaskRequest;
use App\Http\Requests\Marketing\UpdateMindNodeRequest;
use App\Models\KanbanBoard;
use App\Models\KanbanTask;
use App\Models\MarketingStrategy;
use App\Models\MindMap;
use App\Models\MindNode;
use App\Models\SocialMediaPost;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    // Kanban Board
    public function kanban()
    {
        $boards = KanbanBoard::with('tasks')->get();

        return view('marketing.kanban', compact('boards'));
    }

    public function storeKanbanBoard(StoreKanbanBoardRequest $request)
    {
        KanbanBoard::create($request->validated());

        return redirect()->route('marketing.kanban');
    }

    public function mindmap()
    {
        $mindmaps = MindMap::with('nodes')->get();

        return view('marketing.mindmap', compact('mindmaps'));
    }

    public function storeMindMap(StoreMindMapRequest $request)
    {
        $mindMap = MindMap::create($request->validated());
        MindNode::create([
            'mind_map_id' => $mindMap->id,
            'title' => $mindMap->name,
            'content' => 'Root node for '.$mindMap->name,
        ]);

        return redirect()->route('marketing.mindmap')->with('status', 'Mind Map created successfully!');
    }

    public function storeMindNode(StoreMindNodeRequest $request)
    {
        $node = MindNode::create($request->validated());

        return redirect()->route('marketing.mindmap')->with('status', 'Node created successfully!');
    }

    public function storeMindNodeAjax(Request $request)
    {
        $request->validate([
            'mind_map_id' => 'required|exists:mind_maps,id',
            'parent_id' => 'nullable|exists:mind_nodes,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $node = MindNode::create($request->all());

        return response()->json([
            'success' => true,
            'node' => [
                'id' => (string) $node->id,
                'parentid' => $node->parent_id ? (string) $node->parent_id : null,
                'topic' => html_entity_decode($node->title, ENT_QUOTES, 'UTF-8'),
            ],
        ]);
    }

    public function editMindNode(MindNode $node)
    {
        $mindmap = $node->mindMap;
        $nodes = $mindmap->nodes()->where('id', '!=', $node->id)->get();

        return view('marketing.mindmap-node-edit', compact('node', 'mindmap', 'nodes'));
    }

    public function updateMindNode(UpdateMindNodeRequest $request, MindNode $node)
    {
        $node->update($request->validated());

        return redirect()->route('marketing.mindmap')->with('status', 'Node updated successfully!');
    }

    public function destroyMindNode(MindNode $node)
    {
        $rootNode = $node->mindMap->nodes->first();
        if ($node->id === $rootNode->id) {
            return redirect()->route('marketing.mindmap')->with('error', 'Cannot delete the root node!');
        }
        $node->delete();

        return redirect()->route('marketing.mindmap')->with('status', 'Node deleted successfully!');
    }

    public function getMindMapNodes($id)
    {
        $mindmap = MindMap::with('nodes')->findOrFail($id);
        $nodes = $mindmap->nodes;
        if ($nodes->isEmpty()) {
            return response()->json(['error' => 'No nodes found for this Mind Map'], 404);
        }
        $rootNode = $nodes->first();
        $data = [
            'meta' => ['name' => $mindmap->name, 'author' => 'User', 'version' => '1.0'],
            'format' => 'node_tree',
            'data' => [
                ['id' => (string) $rootNode->id, 'isroot' => true, 'topic' => html_entity_decode($rootNode->title, ENT_QUOTES, 'UTF-8')],
            ],
        ];
        foreach ($nodes->skip(1) as $node) {
            $data['data'][] = [
                'id' => (string) $node->id,
                'parentid' => (string) ($node->parent_id ?? $rootNode->id),
                'topic' => html_entity_decode($node->title, ENT_QUOTES, 'UTF-8'),
            ];
        }

        return response()->json($data);
    }

    // Marketing Strategy
    public function strategy()
    {
        $strategies = MarketingStrategy::all();

        return view('marketing.strategy', compact('strategies'));
    }

    public function storeStrategy(StoreMarketingStrategyRequest $request)
    {
        $data = $request->validated();
        $action = new GenerateStrategyAction;
        $data['generated_content'] = $action->execute($data['prompt']);
        MarketingStrategy::create($data);

        return redirect()->route('marketing.strategy');
    }

    // Social Media Marketing
    public function social()
    {
        $posts = SocialMediaPost::all();

        return view('marketing.social', compact('posts'));
    }

    public function storeSocial(StoreSocialMediaPostRequest $request)
    {
        $data = $request->validated();
        $action = new GenerateSocialPostAction;
        $data['generated_content'] = $action->execute($data['prompt']);
        SocialMediaPost::create($data);

        return redirect()->route('marketing.social');
    }

    public function storeKanbanTask(StoreKanbanTaskRequest $request)
    {
        KanbanTask::create($request->validated());

        return redirect()->route('marketing.kanban')->with('status', 'Task created successfully!');
    }

    public function updateKanbanTask1(UpdateKanbanTaskRequest $request, KanbanTask $task)
    {
        $task->update($request->validated());

        return redirect()->route('marketing.kanban')->with('status', 'Task updated successfully!');
    }

    // New method for deleting a Kanban task
    public function destroyKanbanTask(KanbanTask $task)
    {
        $task->delete();

        return redirect()->route('marketing.kanban')->with('status', 'Task deleted successfully!');
    }

    // Existing updateKanbanTask for drag-and-drop
    public function updateKanbanTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:kanban_tasks,id',
            'status' => 'required|in:todo,doing,done',
        ]);

        $task = KanbanTask::find($request->task_id);
        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
}
